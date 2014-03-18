<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\dispatcher;

use umi\hmvc\component\IComponent;
use umi\hmvc\dispatcher\Dispatcher as FrameworkDispatcher;
use umicms\exception\RuntimeException;

/**
 * {@inheritdoc}
 */
class Dispatcher extends FrameworkDispatcher
{

    /**
     * Возвращает компонент по его пути.
     * @param string $componentPath путь до компонента
     * @throws RuntimeException если не удалось определить компонент
     * @return IComponent
     */
    public function getComponentByPath($componentPath)
    {
        $componentPathParts = explode(IComponent::PATH_SEPARATOR, $componentPath);
        $component = $this->getInitialComponent();

        if ($component->getName() != array_shift($componentPathParts)) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot resolve component path "{path}".',
                    ['path' => $componentPath]
                )
            );
        }

        while ($componentName = array_shift($componentPathParts)) {
            $component = $component->getChildComponent($componentName);
        }

        return $component;
    }

}
 