<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\api\controller;

use umicms\project\admin\layout\CollectionComponentLayout;


/**
 * Контроллер вывода настроек компонента
 */
class DefaultRestSettingsController extends BaseDefaultRestController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $layout = new CollectionComponentLayout($this->getComponent());


        return $this->createViewResponse(
            'settings',
            [
                'settings' => $layout->build()
            ]
        );
    }


}