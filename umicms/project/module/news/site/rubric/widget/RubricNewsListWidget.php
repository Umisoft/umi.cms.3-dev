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
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsRubric;

/**
 * Виджет для вывода списка новостей по рубрикам.
 */
class RubricNewsListWidget extends BaseListWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'newsList';
    /**
     * @var array|NewsRubric[]|NewsRubric|null $rubrics рубрика, список новостных рубрик или GUID, из которых выводятся новости.
     * Если не указаны, то новости выводятся из всех рубрик
     */
    public $rubrics;

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
    protected function getSelector()
    {
        $rubrics = (array) $this->rubrics;

        foreach ($rubrics as &$rubric) {
            if (is_string($rubric)) {
                $rubric = $this->module->rubric()->get($rubric);
            }

            if (!$rubric instanceof NewsRubric) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'rubrics',
                            'class' => NewsRubric::className()
                        ]
                    )
                );
            }
        }

        return  $this->module->getNewsByRubrics($rubrics);
    }
}
 