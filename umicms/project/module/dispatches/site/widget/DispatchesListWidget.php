<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\widget;

use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\dispatches\model\DispatchModule;

/**
 * Виджет для вывода списка рассылок
 */
class DispatchesListWidget extends BaseListWidget
{
    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * Конструктор.
     * @param DispatchModule $dispatchApi модуль "Рассылки"
     */
    public function __construct(DispatchModule $dispatchApi)
    {
        $this->module = $dispatchApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        return $this->module->getDispatches();
    }
}
 