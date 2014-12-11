<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\conference\view\widget;

use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\forum\model\ForumModule;

/**
 * Виджет для вывода списка конференций.
 */
class ListWidget extends BaseListWidget
{
    /**
     * @var ForumModule $module модуль "Форум"
     */
    protected $module;

    /**
     * Конструктор.
     * @param ForumModule $module модуль "Форум"
     */
    public function __construct(ForumModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        return $this->module->getConference();
    }
}
 