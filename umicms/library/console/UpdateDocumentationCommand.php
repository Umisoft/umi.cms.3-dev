<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use project\module\structure\model\object\ControllerPage;
use project\module\structure\model\object\WidgetPage;
use Sami\Parser\Filter\TrueFilter;
use Sami\Project;
use Sami\Reflection\ClassReflection;
use Sami\Reflection\MethodReflection;
use Sami\Reflection\PropertyReflection;
use Sami\Sami;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use umi\hmvc\IMvcEntityFactory;
use umi\hmvc\widget\IWidget;
use umi\orm\collection\ICollectionManager;
use umi\orm\persister\IObjectPersister;
use umi\toolkit\IToolkit;
use umicms\exception\InvalidObjectsException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\component\site\SiteComponent;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\module\search\model\SearchModule;
use umicms\project\module\structure\model\collection\StructureElementCollection;
use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\users\model\UsersModule;
use umicms\project\site\SiteApplication;

/**
 * Обновляет документацию
 */
class UpdateDocumentationCommand extends BaseProjectCommand
{
    /**
     * @var Project $samiProject
     */
    private $samiProject;
    /**
     * @var IToolkit $toolkit
     */
    private $toolkit;
    /**
     * @var StructureElementCollection $structureCollection
     */
    private $structureCollection;
    /**
     * @var OutputInterface $output
     */
    private $output;
    /**
     * @var array $projectConfig
     */
    private $projectConfig;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('update:documentation')
            ->setDescription('Updates UMI.CMS documentation');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bootstrap = $this->dispatchToProject($input, $output);
        $this->projectConfig = $bootstrap->getProjectConfig();
        $this->toolkit = $bootstrap->getToolkit();
        $this->output = $output;

        /**
         * @var ICollectionManager $collectionManager
         */
        $collectionManager =  $this->toolkit->getService('umi\orm\collection\ICollectionManager');
        $this->structureCollection = $collectionManager->getCollection('structure');

        $this->output->writeln('<process>Updating documentation...</process>');
        $this->buildDocsStructure();
        $this->commit();
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
         * @var StaticPage $widgetsPage
         */
        $widgetsPage = $this->structureCollection->get('d7dda227-cac7-474d-ab0d-d361d0bc16a3');
        /**
         * @var StaticPage $controllersPage
         */
        $controllersPage = $this->structureCollection->get('fda552a8-846a-431d-87bf-ed719cdd884b');

        /**
         * @var CmsDispatcher $dispatcher
         */
        $dispatcher =  $this->toolkit->getService('umi\hmvc\dispatcher\IDispatcher');
        /**
         * @var IMvcEntityFactory $mvcEntityFactory
         */
        $mvcEntityFactory = $this->toolkit->getService('umi\hmvc\IMvcEntityFactory');
        $dispatcher->setInitialComponent($mvcEntityFactory->createComponent('project', 'project', $this->projectConfig));

        /**
         * @var SiteApplication $siteApplication
         */
        $siteApplication = $dispatcher->getComponentByPath('project.site');

        foreach ($siteApplication->getChildComponentNames() as $componentName) {
            /**
             * @var SiteComponent $childComponent
             */
            $childComponent = $siteApplication->getChildComponent($componentName);
            $this->buildComponentStructure($childComponent, $widgetsPage, 'widgets');
            $this->buildComponentStructure($childComponent, $controllersPage, 'controllers');
        }

    }

    protected function buildComponentStructure(SiteComponent $component, StaticPage $parentPage, $type)
    {
        $this->output->write('.');
        try {
            /**
             * @var StaticPage $page
             */
            $page = $this->structureCollection->getByUri($parentPage->getURL() . '/' . $component->getName());
        } catch (NonexistentEntityException $e) {

            $page = $this->structureCollection->add($component->getName(), StaticPage::TYPE, $parentPage);

            $name = substr($component->getPath(), strlen('project.site') + 1);
            $page->setValue('inMenu', true)
                ->setValue('displayName', $name)
                ->setValue('submenuState', StaticPage::SUBMENU_ALWAYS_SHOWN)
                ->setValue('active', true);
        }

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
                $this->output->write('.');
                $this->buildWidgetPage($component->getWidget($widgetName),$component, $page);
            }
        }

        if ($type === 'controllers') {
            $controllerNames = $component->getControllerNames();
            foreach ($controllerNames as $controllerName) {
                $this->output->write('.');
                $this->buildControllerPage($component->getController($controllerName), $component, $page);
            }

        }

    }

    protected function buildControllerPage(BaseCmsController $controller, SiteComponent $component, StaticPage $parentPage)
    {
        $className = get_class($controller);
        $class = $this->samiProject->getClass($className);

        /**
         * @var ControllerPage $controllerPage
         */
        try {
            $controllerPage = $this->structureCollection->getByUri($parentPage->getURL() . '/' . $controller->getName());
        } catch (NonexistentEntityException $e) {
            $controllerPage = $this->structureCollection->add($controller->getName(), ControllerPage::TYPE, $parentPage)
                ->setValue('inMenu', true)
                ->setValue('active', true);

            $controllerPage->displayName = substr($component->getPath(), strlen('project.site') + 1) . '.' . $controller->getName();
            $controllerPage->h1 = rtrim($class->getShortDesc(), '.');
            $controllerPage->metaTitle = rtrim($class->getShortDesc(), '.');
            $controllerPage->active = true;
        }

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

            return '<h2 class="table-header">Переменные, доступные в шаблоне</h2>' .
            '<table>
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

            $description .= '<h2 class="table-header"><a name="' . $name. '"></a>' . $name;

            if ($shortDesc = $class->getShortDesc()) {
                $description .= '<span class="sub small">' . $shortDesc . '</span>';
            }

            $description .= '</h2>';

            if ($longDesc = $class->getLongDesc()) {
                $description .= '<p>' . $longDesc . '</p>';
            }

            $description .= $table;

            $type = '<a href="#' . $name . '">' . $name . '</a>';
        }

        return [$type, $description];
    }

    protected function buildWidgetPage(IWidget $widget, SiteComponent $component, StaticPage $parentPage)
    {
        $className = get_class($widget);
        $class = $this->samiProject->getClass($className);

        /**
         * @var WidgetPage $widgetPage
         */
        try {
            $widgetPage = $this->structureCollection->getByUri($parentPage->getURL() . '/' . $widget->getName());
        } catch (NonexistentEntityException $e) {
            $widgetPage = $this->structureCollection->add($widget->getName(), WidgetPage::TYPE, $parentPage)
                ->setValue('inMenu', true)
                ->setValue('active', true);

            $widgetPage->displayName = substr($component->getPath(), strlen('project.site') + 1) . '.' . $widget->getName();
            $widgetPage->h1 = rtrim($class->getShortDesc(), '.');
            $widgetPage->metaTitle = rtrim($class->getShortDesc(), '.');
            $widgetPage->active = true;
        }

        $description = '';
        if ($longDescription = $class->getLongDesc()) {
            $description .= '<p>' . $longDescription . '</p>';
        }

        $widgetPage->description = $description;
        $widgetPage->parameters = '<h2 class="table-header">Параметры вызова виджета</h2>'.$this->buildPublicPropertiesDescriptionTable($class, $widget);
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
                '<table>
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
                        /**
                         * @var PropertyReflection $parentProperty
                         */
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

            $hintDesc = rtrim($hintDesc, '.');

            if (!$hintDesc) {
                throw new RuntimeException('Cannot update public properties for class "' . $class->getName() .'". Property "' . $name . '" has no description.');
            }

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
                '<table>
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
         * @var IObjectPersister $objectPersister
         */
        $objectPersister =  $this->toolkit->getService('umi\orm\persister\IObjectPersister');
        /**
         * @var SearchModule $searchModule
         */
        $searchModule = $this->toolkit->getService('umicms\module\IModule', SearchModule::className());
        /**
         * @var UsersModule $usersModule
         */
        $usersModule = $this->toolkit->getService('umicms\module\IModule', UsersModule::className());

        $searchIndexApi = $searchModule->getSearchIndexApi();
        $currentUser = $usersModule->user()->get('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');

        foreach ($objectPersister->getModifiedObjects() as $object) {
            $collection = $object->getCollection();
            if ($collection instanceof IRecoverableCollection && $object instanceof IRecoverableObject) {
                $collection->createBackup($object);
            }
            if ($object instanceof ICmsPage) {
                $searchIndexApi->buildIndexForObject($object);
            }
        }

        /**
         * @var ICmsObject|ICmsPage $object
         */
        foreach ($objectPersister->getNewObjects() as $object) {
            if ($object instanceof ICmsPage) {
                $searchIndexApi->buildIndexForObject($object);
            }
        }

        foreach ($objectPersister->getNewObjects() as $object) {
            $object->owner = $currentUser;
            $object->setCreatedTime();
        }

        foreach ($objectPersister->getModifiedObjects() as $object) {
            $object->editor = $currentUser;
            $object->setUpdatedTime();
        }

        if ($invalidObjects = $objectPersister->getInvalidObjects()) {
            $this->output->writeln('<error>Validation errors</error>');

            $table = new Table($this->output);
            $table->setHeaders(['Guid', 'Type', 'DisplayName', 'Property', 'Error']);

            /**
             * @var ICmsObject $object
             */
            foreach ($invalidObjects as $object) {
                foreach ($object->getValidationErrors() as $propertyName => $errors) {
                    foreach ($errors as $error) {
                        $table->addRow([$object->guid, $object->getTypePath(), $object->displayName, $propertyName, $error]);
                    }
                }
                $table->addRow(new TableSeparator());
            }

            $table->render();
        } else {
            $this->output->writeln('<info>Persisting objects...</info>');

            $table = new Table($this->output);
            $table->setHeaders(['Guid', 'Type', 'DisplayName', 'State']);

            foreach ($objectPersister->getNewObjects() as $object) {
                if ($object instanceof ICmsPage) {
                    $table->addRow([$object->guid, $object->getTypePath(), $object->displayName, 'added']);
                }
            }

            foreach ($objectPersister->getModifiedObjects() as $object) {
                if ($object instanceof ICmsPage) {
                    $table->addRow([$object->guid, $object->getTypePath(), $object->displayName, 'updated']);
                }
            }

            foreach ($objectPersister->getDeletedObjects() as $object) {
                if ($object instanceof ICmsPage) {
                    $table->addRow([$object->guid, $object->getTypePath(), $object->displayName, 'deleted']);
                }
            }

            $table->render();

            $objectPersister->commit();
            $this->output->writeln('<process>Complete</process>');
        }

    }

    private function commitDump($projectDirectory)
    {
        $process = new Process('git commit -m \'Update documentation dump.\'', $projectDirectory);
        if ($process->run() != 0) {
            throw new \RuntimeException('Cannot commit documentation changes.');
        }

        $process = new Process('git push', $projectDirectory);
        if ($process->run() != 0) {
            throw new \RuntimeException('Cannot push documentation changes.');
        }
    }

}