<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\update\controller;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\project\module\service\admin\update\layout\UpdateComponentLayout;
use umicms\project\module\service\model\ServiceModule;

/**
 * Контроллер вывода настроек компонента
 */
class LayoutController extends BaseLayoutController
{
    /**
     * @var ServiceModule $service
     */
    protected $service;

    /**
     * Конструктор.
     * @param ServiceModule $serviceModule
     */
    public function __construct(ServiceModule $serviceModule)
    {
        $this->service = $serviceModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLayout()
    {
        return new UpdateComponentLayout($this->getComponent(), $this->service->update());
    }
}
