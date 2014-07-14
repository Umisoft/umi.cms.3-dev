<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsRubric;

/**
 * Виджет вывода рубрики.
 */
class RubricWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|NewsRubric $rubric рубрика или GUID рубрики
     */
    public $rubric;

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
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> NewsRubric $rubric новостная рубрика</li>
     * </ul>
     *
     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->rubric)) {
            $this->rubric = $this->module->rubric()->get($this->rubric);
        }

        if (!$this->rubric instanceof NewsRubric) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'rubric',
                        'class' => NewsRubric::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'rubric' => $this->rubric
            ]
        );
    }
}
 