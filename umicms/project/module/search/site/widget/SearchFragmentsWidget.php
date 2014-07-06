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

use Exception;
use umi\http\THttpAware;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\orm\object\ICmsPage;
use umicms\project\module\search\model\SearchApi;
use umicms\project\module\search\model\SearchIndexApi;

/**
 * Виджет, выводящий подсвеченные фрагменты-цитаты результата поиска.
 */
class SearchFragmentsWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'fragments';
    /**
     * @var ICmsPage $result страница, которая попала в результат поиска
     */
    public $page;
    /**
     * @var string $query запрос, по которому найден результат
     */
    public $query;
    /**
     * @var int $contextWordsLimit сколько слов контекста выводить в цитате
     */
    public $contextWordsLimit = 5;

    /**
     * @var SearchIndexApi $api API индексации модуля "Поиск"
     */
    protected $indexApi;
    /**
     * @var SearchApi $searchApi API поиска модуля "Поиск"
     */
    private $searchApi;

    /**
     * Конструктор.
     * @param SearchApi $searchApi
     * @param SearchIndexApi $searchIndexApi
     */
    public function __construct(SearchApi $searchApi, SearchIndexApi $searchIndexApi)
    {
        $this->indexApi = $searchIndexApi;
        $this->searchApi = $searchApi;
    }

    /**
     * Вывод фрагментов. Если найденный текст не содержит точного свопадения с запросом — фрагменты не выводятся.
     */
    public function __invoke()
    {
        if (!$this->page instanceof ICmsPage) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Option "{option}" should be instance of class "{class}".',
                    ['option' => 'page', 'class' => 'umicms\orm\object\ICmsPage']
                )
            );
        }

        $content = $this->indexApi->extractSearchableContent($this->page);
        try {
            $fragmenter = $this->searchApi->getResultFragmented($this->query, $content)
                ->fragmentize($this->contextWordsLimit);

            return $this->createResult(
                $this->template,
                [
                    'query' => $this->query,
                    'fragmenter' => $fragmenter,
                ]
            );
        } catch (Exception $e) {
            return '';
        }

    }
}
