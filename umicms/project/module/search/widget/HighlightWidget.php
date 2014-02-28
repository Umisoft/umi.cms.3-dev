<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\widget;

use umi\http\THttpAware;
use umicms\base\widget\BaseWidget;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\search\api\SearchApi;

/**
 * Виджет вывода сюжета новостей
 */
class HighlightWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/highlight';

    /**
     * @var string $text
     */
    public $text;
    /**
     * @var string $query
     */
    public $query;
    /**
     * Настройка маркера начала подсветки найденных результатов
     * @var string $searchHighlightStart
     */
    public $highlightStart = '<mark>';

    /**
     * Настройка маркера конца подсветки найденных результатов
     * @var string $searchHighlightEnd
     */
    public $highlightEnd = '</mark>';

    /**
     * @var NewsPublicApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param SearchApi $searchApi API модуля "Новости"
     * @internal param \umi\http\Request $request
     */
    public function __construct(SearchApi $searchApi)
    {
        $this->api = $searchApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->api->highlightResult(
            $this->query,
            $this->text,
            $this->highlightStart,
            $this->highlightEnd
        );
    }
}
