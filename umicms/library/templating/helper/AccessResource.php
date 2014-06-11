<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\templating\helper;

use umi\hmvc\component\IComponent;
use umicms\hmvc\dispatcher\CmsDispatcher;

/**
 * Помощник шаблонов для проверки доступа к ресурсам.
 */
class AccessResource
{
    /**
     * @var CmsDispatcher $dispatcher диспетчер
     */
    protected $dispatcher;

    /**
     * Конструктор.
     * @param CmsDispatcher $dispatcher диспетчер
     */
    public function __construct(CmsDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Проверяет доступ к ресурсу.
     * @param string $componentPath путь до компонента
     * @param array $resources список ресурсов для проверки
     * @return bool
     */
    public function isAllowedResource($componentPath, $resources)
    {
        $resourcesList = (array) $resources;

        $component = $this->dispatcher->getComponentByPath(
            CmsDispatcher::SITE_COMPONENT_PATH . IComponent::PATH_SEPARATOR . $componentPath
        );

        foreach ($resourcesList as $resource) {
            if (!$this->dispatcher->checkPermissions($component, $resource)) {
                return false;
            }
        }

        return true;
    }
}
 