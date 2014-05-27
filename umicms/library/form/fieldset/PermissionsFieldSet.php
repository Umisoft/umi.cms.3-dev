<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\form\fieldset;

use umi\form\element\Hidden;
use umi\form\element\IFormElement;
use umi\form\element\MultiSelect;
use umi\form\fieldset\FieldSet;
use umi\form\IFormAware;
use umi\form\TFormAware;
use umi\hmvc\dispatcher\IDispatcher;
use umicms\hmvc\component\BaseComponent;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\project\module\users\api\object\UserGroup;
use umicms\project\site\SiteApplication;

/**
 * Класс группы сужностей для редактирования прав.
 */
class PermissionsFieldSet extends FieldSet implements IFormAware
{
    use TFormAware;

    /**
     * Тип элемента формы.
     */
    const TYPE_NAME = 'permissionsFieldset';
    /**
     * @var CmsDispatcher $dispatcher
     */
    private $dispatcher;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, array $attributes = [], array $options = [], IDispatcher $dispatcher)
    {
        parent::__construct($name, $attributes, $options);
        $this->dispatcher = $dispatcher;

        $this->addElements();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->getRolesInput()->setValue($data);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getRolesInput()->getValue();
    }

    /**
     * Возвращает скрытый инпут для хранения прав
     * @return IFormElement
     */
    private function getRolesInput()
    {
        /**
         * @var IFormElement $rolesInput
         */
        $rolesInput = $this->get(UserGroup::FIELD_ROLES);

        return $rolesInput;
    }

    private function addElements()
    {
        $rolesInput = $this->createFormEntity(
            UserGroup::FIELD_ROLES,
            [
                'type' => Hidden::TYPE_NAME,
                'options' => [
                    'dataSource' => UserGroup::FIELD_ROLES
                ]
            ]
        );

        $this->add($rolesInput);

        $project = $this->dispatcher->getInitialComponent();
        /**
         * @var SiteApplication $siteApplication
         */
        $siteApplication = $project->getChildComponent('site');

        $this->formElementForComponent($siteApplication);

    }

    private function formElementForComponent(BaseComponent $component)
    {
        $componentRoles = $component->getAclManager()->getRoleList();
        if ($componentRoles) {

            $choices = array_combine(array_keys($componentRoles), array_keys($componentRoles));
            $input = $this->createFormEntity(
                $component->getPath(),
                [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => $component->getPath(),
                    'options' => [
                        'choices' => $choices
                    ]
                ]
            );
            $this->add($input);
        }

        foreach($component->getChildComponentNames() as $name) {
            $this->formElementForComponent($component->getChildComponent($name));
        }
    }
}
 