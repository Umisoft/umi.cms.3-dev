<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\form\element;

use umi\form\element\BaseFormElement;
use umi\form\FormEntityView;
use umi\hmvc\dispatcher\IDispatcher;
use umi\i18n\translator\ITranslator;
use umicms\hmvc\component\BaseComponent;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\project\admin\AdminApplication;
use umicms\project\site\SiteApplication;

/**
 * Класс группы сущностей для редактирования прав.
 */
class Permissions extends BaseFormElement
{
    /**
     * Тип элемента формы.
     */
    const TYPE_NAME = 'permissions';
    /**
     * {@inheritdoc}
     */
    protected $type = 'permissions';
    /**
     * @var CmsDispatcher $dispatcher
     */
    private $dispatcher;
    /**
     * @var ITranslator $translator
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, array $attributes = [], array $options = [], IDispatcher $dispatcher, ITranslator $translator)
    {
        parent::__construct($name, $attributes, $options);
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    protected function extendView(FormEntityView $view)
    {
        parent::extendView($view);

        $view->resources = $this->buildResources();
    }

    /**
     * Возвращает список возможных ресурсов и ролей для них
     * @return array
     */
    private function buildResources()
    {
        $project = $this->dispatcher->getInitialComponent();
        /**
         * @var SiteApplication $siteApplication
         */
        $siteApplication = $project->getChildComponent('site');
        /**
         * @var AdminApplication $adminApplication
         */
        $adminApplication = $project->getChildComponent('admin');

        $resources = [];
        $this->buildResourcesForComponent($siteApplication, $resources);
        $this->buildResourcesForComponent($adminApplication, $resources);

        return $resources;

    }

    /**
     * Формирует список ресурсов и ролей для них у компонента
     * @param BaseComponent $component
     * @param array $resources
     */
    private function buildResourcesForComponent(BaseComponent $component, array &$resources)
    {
        $componentRoles = $component->getAclManager()->getRoleList();
        if ($componentRoles) {

            $resourceInfo = [
                'path' => $component->getPath(),
                'label' =>
                    $this->translator->translate(
                        $component->getDictionariesNames(), 'component:' . $component->getName() . ':displayName'
                    ),
                'roles' => [],
            ];
            foreach ($componentRoles as $name => $parentRoles) {
                $resourceInfo['roles'][] = [
                    'label' =>
                        $this->translator->translate(
                            $component->getDictionariesNames(), 'role:' . $name . ':displayName'
                        ),
                    'value' => $name
                ];
            }
            $resources[] = $resourceInfo;
        }

        foreach($component->getChildComponentNames() as $name) {
            $this->buildResourcesForComponent($component->getChildComponent($name), $resources);
        }
    }
}
 