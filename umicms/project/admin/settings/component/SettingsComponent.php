<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\settings\component;

use umi\acl\IAclResource;
use umicms\hmvc\component\BaseComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * Компонент настроек.
 */
class SettingsComponent extends BaseComponent implements IAclResource, IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Префикс имени ACL-ресурса
     */
    const ACL_RESOURCE_PREFIX = 'component:';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }

    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        return [
            'name'        => $this->getName(),
            'displayName' => $this->translate('component:' . $this->getName() . ':displayName'),
            'resource' => $this->getUrlManager()->getSettingsComponentResourceUrl($this)
        ];
    }

}
 