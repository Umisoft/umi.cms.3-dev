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

use umicms\hmvc\component\admin\collection\ItemController as BaseItemController;
use umicms\orm\object\ICmsObject;
use umicms\project\module\seo\model\HttpConfigsModule;

class ItemController extends BaseItemController
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

    protected function save(ICmsObject $object, array $data)
    {
        $result = parent::save($object, $data);
        $this->refreshHttpConfigs();
        return $result;
    }

    protected function delete(ICmsObject $object)
    {
        parent::delete($object);
        $this->refreshHttpConfigs();
    }

} 