<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\item\widget;

use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\model\NewsModule;

/**
 * Виджет для вывода списка последних новостей
 */
class NewsItemListWidget extends BaseListWidget
{
    /**
     * @var NewsModule $module модуль "Новости"
     */
    protected $module;

    /**
     * Конструктор.
     * @param NewsModule $newsApi модуль "Новости"
     */
    public function __construct(NewsModule $newsApi)
    {
        $this->module = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        return $this->module->getNews();
    }
}
 