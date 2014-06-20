<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout\control;

use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Административный контрол для вывода меню из дочерних компонентов
 */
class ComponentsMenuControl extends AdminControl
{
    /**
     * @var IUrlManager $urlManager URL-менеджер
     */
    protected $urlManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        $this->urlManager = $urlManager;
        parent::__construct($component);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureParams()
    {
        $this->params['links'] = [];

        foreach ($this->component->getChildComponentNames() as $name) {
            /**
             * @var AdminComponent $childComponent
             */
            $childComponent = $this->component->getChildComponent($name);

            $this->params['links'][] = [
                'name' => $name,
                'resource' => $this->urlManager->getAdminComponentResourceUrl($childComponent),
                'label' => $childComponent->translate(
                    'component:' . $name . ':displayName'
                )
            ];
        }
    }
}
 