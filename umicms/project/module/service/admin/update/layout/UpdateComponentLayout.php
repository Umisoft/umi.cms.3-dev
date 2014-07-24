<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\update\layout;

use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\project\module\service\model\UpdateApi;

/**
 * Билдер сетки админского компонента Update.
 */
class UpdateComponentLayout extends AdminComponentLayout
{
    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, UpdateApi $updateApi)
    {
        $this->dataSource = [
            'currentVersion' => $updateApi->getLatestVersionInfo(),
            'latestVersion' => $updateApi->getLatestVersionInfo(),
        ];

        parent::__construct($component);
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

}
 