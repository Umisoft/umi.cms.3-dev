<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\component;

use umicms\hmvc\component\BaseComponent;

/**
 * Компонент административной панели.
 */
class AdminComponent extends BaseComponent
{

    /**
     * Возвращает информацию о компоненте и дочерних компонентах на всю глубину.
     * @return array
     */
    public function getComponentInfo()
    {
        $componentInfo = [
            'name'        => $this->getName(),
            'displayName' => $this->translate($this->getName() . ':displayName'),
            'path'        => $this->getPath()
        ];

        $components = [];

        foreach ($this->getChildComponentNames() as $componentName) {
            /**
             * @var AdminComponent $component
             */
            $component = $this->getChildComponent($componentName);
            $components[] = $component->getComponentInfo();
        }

        if ($components) {
            $componentInfo['components'] = $components;
        }

        return $componentInfo;
    }

}
 