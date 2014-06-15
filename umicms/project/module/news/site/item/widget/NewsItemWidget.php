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

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsItem;

/**
 * Виджет вывода новости.
 */
class NewsItemWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|NewsItem $newsItem новость или GUID новости
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
    public function __invoke()
    {
        if (is_string($this->newsItem)) {
            $this->newsItem = $this->module->news()->get($this->newsItem);
        }

        if (!$this->newsItem instanceof NewsItem) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'newsItem',
                        'class' => NewsItem::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'newsItem' => $this->newsItem
            ]
        );
    }
}
 