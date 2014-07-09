<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\recycle\controller;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\project\module\service\admin\recycle\layout\RecycleComponentLayout;

/**
 * Контроллер вывода настроек компонента.
 */
class LayoutController extends BaseLayoutController implements ICollectionManagerAware
{
    use TCollectionManagerAware;
    /**
     * {@inheritdoc}
     */
    protected function getLayout()
    {
        return new RecycleComponentLayout($this->getComponent(), $this->getUrlManager(), $this->getCollectionManager());
    }
}
