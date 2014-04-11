<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\project\module\news\api\NewsApi;
use umicms\hmvc\widget\BaseWidget;

/**
 * Виджет для вывода URL на RSS-ленту по рубрике.
 */
class RubricNewsRssUrlWidget extends BaseWidget
{
    /**
     * @var array $rubricGuid список GUID новостных сюжетов, из которых выводятся новости.
     * Если не указаны, то выводятся новости всех сюжетов
     */
    public $rubricGuid;

    /**
     * @var NewsApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $newsApi API модуля "Новости"
     */
    public function __construct(NewsApi $newsApi)
    {
        $this->api = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $url = $this->api->rubric()->get($this->rubricGuid)->getURL();
        return $this->getUrl('rss', ['url' => $url]);
    }
}
 