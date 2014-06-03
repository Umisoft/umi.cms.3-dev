<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\files\admin\manager\controller;

use umi\hmvc\controller\BaseSecureController;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends BaseSecureController
{
    private $controls = [
        'fileManager' => [
            'action' => '/connector'
        ]
    ];

    private $layout = [
        'contents' => [
            'emptyContext' => [
                'controls' => ['fileManager']
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected function getSettings()
    {
        return [
            'controls' => $this->controls,
            'layout' => $this->layout
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    /**
     * @return DefaultQueryAdminComponent
     */
    protected function getComponent()
    {
        return $this->getContext()->getComponent();
    }
}
