<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\statistics\admin\metrika\layout;

use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\hmvc\url\IUrlManager;

/**
 * Билдер сетки админского компонента YandexMetrika.
 */
class MetrikaComponentLayout extends AdminComponentLayout
{
    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        parent::__construct($component);

        $this->component = $component;

        $this->dataSource = [
            'type' => 'static',
            'objects' => []
        ];

        $this->configureEmptyContextControls();

        $this->configureDynamicControl();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureEmptyContextControls()
    {
        $control = new AdminControl($this->component);
        $control->params['action'] = 'counters';
        $this->addEmptyContextControl('simpleTable', $control);
    }

    /**
     * Конфигурирует динамический контрол.
     */
    private function configureDynamicControl()
    {
        $dynamicControl = new AdminControl($this->component);
        $dynamicControl->params['action'] = 'navigation';

        $this->addSelectedContextControl('dynamic', $dynamicControl);
    }
}
 