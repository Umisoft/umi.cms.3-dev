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

use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\search\model\SearchModule;

/**
 * Виджет, выделяющий подстроку с учетом морфологии в тексте
 */
class HighlightWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'highlight';

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
     * Возвращает размеченную строку.
     * @return string
     */
    public function __invoke()
    {
        return $this->module->getSearchApi()->highlightResult(
            $this->query,
            $this->text,
            $this->highlightStart,
            $this->highlightEnd
        );
    }
}
