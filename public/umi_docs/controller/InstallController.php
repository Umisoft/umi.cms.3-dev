<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace project\umi_docs\controller;

use project\umi_docs\module\structure\model\object\ControllerPage;
use project\umi_docs\module\structure\model\object\WidgetPage;
use Sami\Parser\Filter\TrueFilter;
use Sami\Project;
use Sami\Reflection\ClassReflection;
use Sami\Reflection\MethodReflection;
use Sami\Reflection\PropertyReflection;
use Sami\Sami;
use Symfony\Component\Finder\Finder;
use umi\dbal\cluster\IDbCluster;
use umi\dbal\driver\IDialect;
use umi\hmvc\controller\BaseController;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\exception\InvalidObjectsException;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\component\site\SiteComponent;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\orm\dump\ICmsObjectDumpAware;
use umicms\orm\dump\TCmsObjectDumpAware;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\Environment;
use umicms\project\module\search\model\SearchModule;
use umicms\project\module\structure\model\collection\LayoutCollection;
use umicms\project\module\structure\model\collection\StructureElementCollection;
use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\users\model\collection\UserCollection;
use umicms\project\module\users\model\collection\UserGroupCollection;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\Supervisor;
use umicms\project\module\users\model\object\UserGroup;
use umicms\project\module\users\model\UsersModule;
use umicms\project\site\SiteApplication;

/**
 * Контроллер установки проекта документации
 */
class InstallController extends BaseController implements ICmsObjectDumpAware, ICollectionManagerAware, IObjectPersisterAware, IModuleAware
{

    use TCollectionManagerAware;
    use TObjectPersisterAware;
    use TCmsObjectDumpAware;
    use TModuleAware;

    /**
     * @var IDbCluster $dbCluster
     */
    protected $dbCluster;
    /**
     * @var Project $samiProject
     */
    protected $samiProject;

    public function __construct(IDbCluster $dbCluster)
    {
        $this->dbCluster = $dbCluster;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        header('Content-type: text/plain');

        $connection = $this->dbCluster->getConnection();
        /**
         * @var IDialect $dialect
         */
        $dialect = $connection->getDatabasePlatform();

        $connection->exec($dialect->getDisableForeignKeysSQL());

        $this->dropTables();

        try {
            echo "Sync table schemes...\n";
            foreach ($this->getModules() as $module) {
                $module->getModelCollection()->syncAllSchemes();
            }

            echo "Installing structure...\n";
            $this->installStructure();
            echo "Installing users...\n";
            $this->installUsers();
            echo "Install search...\n";
            $this->installSearch();
            echo "Install components documentation...\n";
            $this->buildDocsStructure();

            $this->commit();

        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
            while ($e = $e->getPrevious()) {
                echo '... ' . $e->getMessage() . "\n";
            }
        }

        exit('Done');
    }

    protected function dropTables()
    {
        $connection = $this->dbCluster->getConnection();

        $tables = $connection->getDriver()->getSchemaManager($connection)->listTableNames();
        foreach ($tables as $table) {
            $connection->getDriver()->getSchemaManager($connection)->dropTable($table);
        }
    }

    protected function installStructure()
    {
        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var LayoutCollection $layoutCollection
         */
        $layoutCollection = $this->getCollectionManager()->getCollection('layout');

        $layoutCollection->add(IObjectType::BASE, 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7')
            ->setValue('fileName', 'layout')
            ->setValue('displayName', 'Основной');

        $structurePage = $structureCollection->add('system', 'system')
            ->setValue('displayName', 'Структура')
            ->setValue('active', true);

        $structurePage->getProperty('locked')->setValue(true);
        $structurePage->getProperty('componentName')->setValue('structure');
        $structurePage->getProperty('componentPath')->setValue('structure');

        $menuPage = $structureCollection->add('menu', 'system', $structurePage)
            ->setValue('displayName', 'Меню')
            ->setValue('active', true);

        $menuPage->getProperty('locked')->setValue(true);
        $menuPage->getProperty('componentName')->setValue('menu');
        $menuPage->getProperty('componentPath')->setValue('structure.menu');

        /**
         * @var StaticPage $main
         */
        $main = $structureCollection->add('main', 'static', null, 'd534fd83-0f12-4a0d-9853-583b9181a948')
            ->setValue('displayName', 'Главная')
            ->setValue('active', true)
            ->setValue('inMenu', true);

        $main->getProperty('componentName')->setValue('structure');
        $main->getProperty('componentPath')->setValue('structure');

    }

    protected function installUsers()
    {
        /**
         * @var UserCollection $userCollection
         */
        $userCollection = $this->getCollectionManager()->getCollection('user');
        /**
         * @var UserGroupCollection $groupCollection
         */
        $groupCollection = $this->getCollectionManager()->getCollection('userGroup');

        /**
         * @var UserGroup $visitors
         */
        $visitors = $groupCollection->add(IObjectType::BASE, 'bedcbbac-7dd1-4b60-979a-f7d944ecb08a')
            ->setValue('displayName', 'Посетители');

        $visitors->getProperty('locked')->setValue(true);

        $visitors->roles = [
            'project' => ['siteExecutor', 'adminExecutor'],
            'project.admin' => ['viewer', 'restExecutor'],
            'project.admin.rest' => ['viewer'],

            'project.site' => [
                'structureExecutor',
                'searchExecutor',
                'viewer',
                'widgetExecutor'
            ],

            'project.site.search' => [
                'viewer'
            ],

            'project.site.structure' => [
                'menuExecutor',
                'viewer'
            ],
            'project.site.structure.menu' => ['viewer']
        ];

        /**
         * @var Supervisor $sv
         */
        $sv = $userCollection->add(Supervisor::TYPE_NAME, '68347a1d-c6ea-49c0-9ec3-b7406e42b01e')
            ->setValue('displayName', 'Супервайзер')
            ->setValue('login', 'sv')
            ->setValue('firstName', 'Супервайзер')
            ->setValue('email', 'sv@umisoft.ru');

        $sv->getProperty('locked')->setValue(true);
        $sv->setPassword('1');

        /**
         * @var Guest $guest
         */
        $guest = $userCollection->add(Guest::TYPE_NAME, '552802d2-278c-46c2-9525-cd464bbed63e')
            ->setValue('displayName', 'Гость')
            ->setValue('displayName', 'Guest', 'en-US');

        $guest->getProperty('locked')->setValue(true);

        $guest->groups->attach($visitors);

    }

    protected function buildDocsStructure()
    {
        $iterator = Finder::create()
            ->files()
            ->in($dir = CMS_DIR);

        $config = [];
        $config['filter'] = function () {
            return new TrueFilter();
        };

        $sami = new Sami($iterator, $config);
        $this->samiProject = $sami['project'];
        $this->samiProject->parse();

        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        $widgetsPage = $structureCollection->add('widgets', StaticPage::TYPE, null, 'd7dda227-cac7-474d-ab0d-d361d0bc16a3');
        $widgetsPage->setValue('inMenu', true)
            ->setValue('displayName', 'Виджеты')
            ->setValue('metaTitle', 'Виджеты')
            ->setValue('active', true);

        $controllersPage = $structureCollection->add('controllers', StaticPage::TYPE, null, 'fda552a8-846a-431d-87bf-ed719cdd884b');
        $controllersPage->setValue('inMenu', true)
            ->setValue('displayName', 'Контроллеры')
            ->setValue('metaTitle', 'Контроллеры')
            ->setValue('active', true);

        /**
         * @var SiteApplication $siteApplication
         */
        $siteApplication = $this->getComponent()->getChildComponent('site');

        foreach ($siteApplication->getChildComponentNames() as $componentName) {
            $childComponent = $siteApplication->getChildComponent($componentName);
            $this->buildComponentStructure($childComponent, $widgetsPage, 'widgets');
            $this->buildComponentStructure($childComponent, $controllersPage, 'controllers');
        }

    }

    protected function buildComponentStructure(SiteComponent $component, StaticPage $parentPage, $type) {

        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        $page = $structureCollection->add($component->getName(), StaticPage::TYPE, $parentPage);

        $name = substr($component->getPath(), strlen('project.site') + 1);
        $page->setValue('inMenu', true)
            ->setValue('displayName', $name)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN)
            ->setValue('active', true);

        foreach ($component->getChildComponentNames() as $childComponentName) {
            /**
             * @var SiteComponent $childComponent
             */
            $childComponent = $component->getChildComponent($childComponentName);
            $this->buildComponentStructure($childComponent, $page, $type);
        }

        if ($type === 'widgets') {
            $widgetNames = $component->getWidgetNames();
            foreach ($widgetNames as $widgetName) {
                $this->buildWidgetPage($component->getWidget($widgetName),$component, $page);
            }
        }

        if ($type === 'controllers') {
            $controllerNames = $component->getControllerNames();
            foreach ($controllerNames as $controllerName) {
                $this->buildControllerPage($component->getController($controllerName), $component, $page);
            }

        }

        if (!$page->childCount) {
            $structureCollection->delete($page);
        }
    }

    protected function buildControllerPage(BaseCmsController $controller, SiteComponent $component, StaticPage $parentPage)
    {
        $className = get_class($controller);
        $class = $this->samiProject->getClass($className);

        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');

        /**
         * @var ControllerPage $controllerPage
         */
        $controllerPage = $structureCollection->add($controller->getName(), ControllerPage::TYPE, $parentPage)
            ->setValue('inMenu', true)
            ->setValue('active', true);

        $controllerPage->displayName = substr($component->getPath(), strlen('project.site') + 1) . '.' . $controller->getName();
        $controllerPage->h1 = rtrim($class->getShortDesc(), '.');
        $controllerPage->metaTitle = rtrim($class->getShortDesc(), '.');
        $controllerPage->active = true;

        $description = '';
        if ($longDescription = $class->getLongDesc()) {
            $description .= '<p>' . $longDescription . '</p>';
        }

        $controllerPage->description = $description;
        $controllerPage->returnValue = $this->getReturnValue($class);

        $properties = $class->getProperties(true);
        if (isset($properties['template'])) {
            $controllerPage->templateName = $controller->template;
        }
    }

    protected function getReturnValue(ClassReflection $class)
    {
        $methods = $class->getMethods(true);

        /**
         * @var MethodReflection $invoke
         */
        $invoke = null;
        if (isset($methods['__invoke'])) {
            $invoke = $methods['__invoke'];
        } elseif($traits = $class->getTraits()) {
            /**
             * @var ClassReflection $trait
             */
            foreach ($traits as $trait) {
                $traitMethods = $trait->getMethods();
                if (isset($traitMethods['__invoke'])) {
                   break;
                }
            }
        }

        $templateParams = [];
        if (isset($invoke) && $invoke->getShortDesc() != '{@inheritdoc}') {
            $templateParams = $invoke->getTags('templateParam');
        }

        if (isset($methods['buildResponseContent'])) {
            /**
             * @var MethodReflection $buildResponseContent
             */
            $buildResponseContent = $methods['buildResponseContent'];
            $templateParams = array_merge($templateParams, $buildResponseContent->getTags('templateParam'));
        }

        if ($templateParams) {
            $templateParamsString = '';
            $additionalContent = '';

            foreach ($templateParams as $templateParam) {
                $additionalContent .= $this->getAdditionalDescription($templateParam);

                $templateParamsString .= '<tr>
          <td>' . $templateParam[0] . '</td>
          <td>' . $templateParam[1] . '</td><td>';

                for ($i = 2; $i < count($templateParam); $i++) {
                    $templateParamsString .= $templateParam[$i] . ' ';
                }

                $templateParamsString .= '</td></tr>';
            }

            return '<h3>Переменные, доступные в шаблоне</h3>' .
                '<table class="table">
          <thead>
            <tr>
              <th>Тип</th>
              <th>Параметр</th>
              <th>Описание</th>
            </tr>
          </thead>
          <tbody>'
                . $templateParamsString .
                '</tbody>
              </table>' . $additionalContent;
        }

        return '';
    }

    protected function getAdditionalDescription(array &$templateParam)
    {

        $result = '';

        $type = &$templateParam[0];

        if (strpos($type, '|')) {
            foreach(explode('|', $type) as $typePart) {
                list($newTypePart, $description)  = $this->buildTypeDescription($typePart);
                str_replace($typePart, $newTypePart, $type);
                $result .= $description;
            }
        } else {
            list($type, $result)  = $this->buildTypeDescription($type);
        }

        return $result;
    }

    protected function buildTypeDescription($type)
    {
        $description = '';
        if (class_exists($type)) {

            $class = $this->samiProject->getClass($type);
            $table = $this->buildAnnotationPropertiesDescriptionTable($class);
            if (!$table) {
                return [$type, ''];
            }

            $nameParts = explode('\\', $type);
            $name = array_pop($nameParts);

            $description .= '<a name="' . $name. '"></a>';
            $description .= '<h4>' . $name . '</h4>';

            if ($shortDesc = $class->getShortDesc()) {
                $description .= '<p>' . $shortDesc . '</p>';
            }

            if ($longDesc = $class->getLongDesc()) {
                $description .= '<p>' . $longDesc . '</p>';
            }

            $description .= $table;

            $type = '<a href="#' . $name . '">' . $name . '</a>';
        }

        return [$type, $description];
    }

    protected function buildWidgetPage(BaseCmsWidget $widget, SiteComponent $component, StaticPage $parentPage)
    {
        $className = get_class($widget);
        $class = $this->samiProject->getClass($className);

        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');

        /**
         * @var WidgetPage $widgetPage
         */
        $widgetPage = $structureCollection->add($widget->getName(), WidgetPage::TYPE, $parentPage)
            ->setValue('inMenu', true)
            ->setValue('active', true);

        $widgetPage->displayName = substr($component->getPath(), strlen('project.site') + 1) . '.' . $widget->getName();
        $widgetPage->h1 = rtrim($class->getShortDesc(), '.');
        $widgetPage->metaTitle = rtrim($class->getShortDesc(), '.');
        $widgetPage->active = true;

        $description = '';
        if ($longDescription = $class->getLongDesc()) {
            $description .= '<p>' . $longDescription . '</p>';
        }

        $widgetPage->description = $description;
        $widgetPage->parameters = '<h3>Параметры вызова виджета</h3>'.$this->buildPublicPropertiesDescriptionTable($class, $widget);
        $widgetPage->returnValue = $this->getReturnValue($class);
    }

    protected function buildAnnotationPropertiesDescriptionTable(ClassReflection $class)
    {
        $properties = [];
        $interfaces = $class->getInterfaces(true);
        /**
         * @var ClassReflection $interface
         */
        foreach ($interfaces as $interface) {
            foreach ($interface->getTags('property') as $property) {
                $properties[$property[1]] = $property;
            }
        }

        $parents = $class->getParent(true);

        /**
         * @var ClassReflection $parent
         */
        foreach ($parents as $parent) {
            foreach ($parent->getTags('property') as $property) {
                $properties[$property[1]] = $property;
            }
        }

        foreach ($class->getTags('property') as $property) {
            $properties[$property[1]] = $property;
        }

        foreach ($class->getTags('property-read') as $property) {
            $properties[$property[1]] = $property;
        }

        if ($properties) {

            $result = '';

            foreach ($properties as $property) {

                $result .= '<tr>
          <td>' . $property[0] . '</td>
          <td>' . $property[1] . '</td><td>';

                for ($i = 2; $i < count($property); $i++) {
                    $result .= $property[$i] . ' ';
                }

                $result .= '</td></tr>';
            }

            return
            '<table class="table">
      <thead>
        <tr>
          <th>Тип</th>
          <th>Параметр</th>
          <th>Описание</th>
        </tr>
      </thead>
      <tbody>'
            . $result .
            '</tbody>
          </table>';
        }

        return '';
    }


    protected function buildPublicPropertiesDescriptionTable(ClassReflection $class, $instance)
    {
        $parameters = '';

        /**
         * @var PropertyReflection $property
         */
        foreach ($class->getProperties(true) as $property) {

            if (!$property->isPublic()) {
                continue;
            }

            $name = $property->getName();

            $hintDesc = $property->getHintDesc();
            $type = $property->getHintAsString();

            $current = $class;
            if (is_null($hintDesc)) {
                /**
                 * @var ClassReflection $parent
                 */
                while ($parent = $current->getParent()) {
                    $parentProperties = $parent->getProperties();
                    if (isset($parentProperties[$name])) {
                        $parentProperty = $parentProperties[$name];
                        if (!is_null($parentProperty->getHintDesc())) {
                            $hintDesc = $parentProperty->getHintDesc();
                            $type = $parentProperty->getHintAsString();
                            break;
                        }
                    }
                    $current = $parent;
                }
            }

            $hintDesc = substr($hintDesc, strlen($name) + 1);

            $default = '';
            if (is_array($property->getDefault())) {
                $default = $instance->{$name};
                if (is_array($default)) {
                    $default = print_r($default, true);
                }
            }

            $parameters .= '<tr>
          <td>' . $name . '</td>
          <td>' . $type . '</td>
          <td>' . $default . '</td>
          <td>' . $hintDesc . '</td>
        </tr>';

        }

        if ($parameters) {
            $parameters =
                '<table class="table">
          <thead>
            <tr>
              <th>Параметр</th>
              <th>Тип</th>
              <th>Значение</th>
              <th>Описание</th>
            </tr>
          </thead>
          <tbody>'
                . $parameters .
                '</tbody>
              </table>';

        }

        return $parameters;
    }

    protected function installSearch()
    {
        /**
         * @var StructureElementCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');

        $searchPage = $structureCollection->add('searching', 'system')
            ->setValue('displayName', 'Поиск')
            ->setValue('active', true);

        $searchPage->getProperty('locked')->setValue(true);
        $searchPage->getProperty('componentName')->setValue('search');
        $searchPage->getProperty('componentPath')->setValue('search');
    }

    /**
     * Записывает изменения всех объектов в БД (бизнес транзакция),
     * запуская перед этим валидацию объектов.
     * Если при сохранении какого-либо объекта возникли ошибки - все изменения
     * автоматически откатываются
     * @throws InvalidObjectsException если объекты не прошли валидацию
     * @throws RuntimeException если транзакция не успешна
     * @return self
     */
    protected function commit()
    {
        /**
         * @var UsersModule $usersModule
         * @var SearchModule $searchModule
         */
        $usersModule = $this->getModuleByClass(UsersModule::className());
        $searchModule = $this->getModuleByClass(SearchModule::className());

        $searchIndexApi = $searchModule->getSearchIndexApi();
        $currentUser = $usersModule->user()->get('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');

        $persister = $this->getObjectPersister();
        /**
         * @var ICmsObject|ICmsPage $object
         */
        foreach ($persister->getNewObjects() as $object) {
            if ($object instanceof ICmsPage) {
                $searchIndexApi->buildIndexForObject($object);
            }
        }

        foreach ($persister->getNewObjects() as $object) {
            $object->owner = $currentUser;
            $object->setCreatedTime();
        }

        $invalidObjects = $persister->getInvalidObjects();

        if (count($invalidObjects)) {
            foreach ($invalidObjects as $object)
            {
                var_dump([$object->getTypePath() . '#' . $object->guid => $object->getValidationErrors()]);
            }

            throw new InvalidObjectsException(
                $this->translate('Cannot persist objects. Objects are not valid.'),
                $invalidObjects
            );
        }

        $this->getObjectPersister()->commit();

    }

}
 