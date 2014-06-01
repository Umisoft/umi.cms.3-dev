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
use umicms\hmvc\widget\BaseWidget;
use umicms\orm\object\ICmsObject;
use umicms\project\module\search\api\SearchApi;
use umicms\project\module\search\api\SearchIndexApi;

/**
 * Виджет, выводящий подсвеченные фрагменты-цитаты результата поиска.
 */
class SearchFragmentsWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'search/fragments';
    /**
     * Результат поиска
     * @var ICmsObject $result
     */
    public $result;
    /**
     * Запрос, по которому найден результат
     * @var string $query
     */
    public $query;
    /**
     * Сколько слов контекста выводить в цитате
     * @var int $contextWordsLimit
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
        $content = $this->indexApi->extractSearchableContent($this->result);
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
