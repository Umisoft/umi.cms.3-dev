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

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\orm\object\ICmsPage;
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
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam string $query поисковый запрос
     * @templateParam umicms\project\module\search\model\highlight\Fragmenter $fragmenter фрагментатор текста по найденным в нем словам
     *
     * @throws InvalidArgumentException
     * @return CmsView
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

        $content = $this->module->getSearchIndexApi()->extractSearchableContent($this->page);
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
