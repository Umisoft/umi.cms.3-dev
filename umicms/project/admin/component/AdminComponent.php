<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\component;

use umicms\hmvc\component\BaseCmsComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * Простой компонент административной панели.
 */
class AdminComponent extends BaseCmsComponent implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Контроллер для выполнения действий
     */
    const ACTION_CONTROLLER = 'action';
    /**
     * Контроллер для отображеня настроек компонента
     */
    const SETTINGS_CONTROLLER = 'settings';
    /**
     * Контроллер для выполнения RUD-операций над объектом
     */
    const ITEM_CONTROLLER = 'item';
    /**
     * Контроллер для выполнения CRUD-операций над списком объектов
     */
    const LIST_CONTROLLER = 'list';

    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        return [
            'name'        => $this->getName(),
            'displayName' => $this->translate('component:' . $this->getName() . ':displayName'),
            'resource' => $this->getUrlManager()->getAdminComponentResourceUrl($this)
        ];
    }
}
 