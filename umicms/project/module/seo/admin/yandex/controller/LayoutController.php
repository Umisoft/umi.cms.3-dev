<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\yandex\controller;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\project\module\seo\admin\yandex\layout\WebmasterComponentLayout;

/**
 * Контроллер вывода настроек компонента.
 */
class LayoutController extends BaseLayoutController
{
    /**
     * {@inheritdoc}
     */
    protected function getLayout()
    {
        return new WebmasterComponentLayout($this->getComponent(), $this->getUrlManager());
    }
}
