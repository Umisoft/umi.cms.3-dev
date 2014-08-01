<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\subject\widget;

use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsItem;

/**
 * Виджет для вывода списка новостных сюжетов.
 */
class SubjectListWidget extends BaseListWidget
{
    /**
     * @var string|NewsItem $newsItem GUID или новость, список сюжетов которой, необходимо вывести
     */
    public $newsItem;

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
        if (isset($this->newsItem) && is_string($this->newsItem)) {
            $this->newsItem = $this->module->news()->get($this->newsItem);
        }

        if ($this->newsItem instanceof NewsItem) {
            return $this->newsItem->subjects->getSelector();
        }

        return $this->module->getSubjects();
    }
}
 