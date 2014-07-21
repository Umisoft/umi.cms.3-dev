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
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\search\model\object\SearchIndex;
use umicms\project\module\search\model\SearchModule;

/**
 * Виджет, выводящий подсвеченные фрагменты-цитаты результата поиска.
 */
class FragmentsWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'fragments';
    /**
     * @var string|SearchIndex $index поисковый индекс или его GUID
     */
    public $index;
    /**
     * @var string $query запрос, по которому найден результат
     */
    public $query;
    /**
     * @var int $contextWordsLimit сколько слов контекста выводить в цитате
     */
    public $contextWordsLimit = 5;

    /**
     * @var SearchModule $api модуль "Поиск"
     */
    protected $module;

    /**
     * Конструктор.
     * @param SearchModule $module
     */
    public function __construct(SearchModule $module)
    {
        $this->module = $module;
    }

    /**
     * Вывод фрагментов. Если найденный текст не содержит точного свопадения с запросом — фрагменты не выводятся.
     */
    public function __invoke()
    {
        $searchIndexApi = $this->module->getSearchIndexApi();

        if (is_string($this->index)) {
            $this->index = $searchIndexApi->getSiteIndexCollection()->get($this->index);
        }

        if (!$this->index instanceof SearchIndex) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'index',
                        'class' => SearchIndex::className()
                    ]
                )
            );
        }

        $content = $this->module->getSearchIndexApi()->extractSearchableContent($this->index->getIndexedObject());

        $fragmenter = $this->module->getSearchApi()->getResultFragmented($this->query, $content)
            ->fragmentize($this->contextWordsLimit);

        return $this->createResult(
            $this->template,
            [
                'query' => $this->query,
                'fragmenter' => $fragmenter,
            ]
        );

    }
}
