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

use umicms\exception\RuntimeException;
use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\TActionController;
use umicms\project\module\service\model\ServiceModule;

/**
 * Контроллер операций компонента Update.
 */
class ActionController extends BaseController
{
    use TActionController;

    /**
     * @var ServiceModule $service
     */
    protected $service;

    /**
     * Конструктор.
     * @param ServiceModule $service
     */
    public function __construct(ServiceModule $service)
    {
        $this->service = $service;
    }

    /**
     * Скачивает и запускает обновление.
     * @throws RuntimeException если невозможно выполнить действие
     * @return string
     */
    protected function actionUpdate()
    {
        return $this->service->update()->downloadUpdate();
    }
}
