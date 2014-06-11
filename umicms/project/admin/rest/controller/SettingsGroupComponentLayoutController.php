<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest\controller;

use umicms\project\admin\controller\base\BaseAdminController;
use umicms\project\admin\layout\SettingsGroupComponentLayout;

/**
 * Контроллер вывода настроек компонента, группирующего компоненты настроек
 */
class SettingsGroupComponentLayoutController extends BaseAdminController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $layout = new SettingsGroupComponentLayout($this->getComponent(), $this->getUrlManager());

        return $this->createViewResponse(
            'layout',
            [
                'layout' => $layout->build()
            ]
        );
    }
}
 