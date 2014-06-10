<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\site\widget;

use umi\http\THttpAware;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\search\api\SearchApi;

/**
 * Виджет, выделяющий подстроку с учетом морфологии в тексте
 */
class HighlightWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/highlight';

    /**
     * Текст, в котором требуется выделить фрагмент
     * @var string $text
     */
    public $text;
    /**
     * Фрагмент текста, который нужно выделить
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
     * @var SearchApi $api API модуля "Поиск"
     */
    protected $api;

    /**
     * Конструктор.
     * @param SearchApi $searchApi API поиска
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
