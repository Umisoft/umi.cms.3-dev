<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\redirects\controller;

use umicms\hmvc\component\admin\collection\ListController as BaseListController;
use umicms\project\module\seo\model\HttpConfigsModule;

class ListController extends BaseListController
{
    /**
     * @var HttpConfigsModule
     */
    private $configsModel;

    public function __construct(HttpConfigsModule $configsModel)
    {
        $this->configsModel = $configsModel;
    }

    private function refreshHttpConfigs()
    {
        $this->configsModel->refresh($this->getUrlManager()->getProjectUrl());
    }

    protected function create(array $data)
    {
        $result = parent::create($data);
        $this->refreshHttpConfigs();
        return $result;
    }

}