<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\update\controller;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
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
        $component = $this->getComponent();

        $layout = new AdminComponentLayout($component);
        $control = new AdminControl($component);

        $currentVersionInfo = $this->service->update()->getCurrentVersionInfo();
        $latestVersionInfo = $this->service->update()->getLatestVersionInfo();

        $control->labels = [
            'Current version' => $component->translate('Current version'),
            'Latest version' => $component->translate('Latest version'),
            'Release date' => $component->translate('Release date'),
            'Nothing update' => $component->translate('Nothing update'),
            'Update available' => $component->translate('Update available')
        ];

        if ($currentVersionInfo['version'] != $latestVersionInfo['version']) {
            $control->params['currentVersion'] = $latestVersionInfo;
            $control->addSubmitButton('update', $control->createActionButton('update'));
        }

        $layout->addEmptyContextControl('update', $control);

        return $layout;
    }
}
