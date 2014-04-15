<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsRubric;

/**
 * Виджет для вывода списка новостей по рубрикам
 */
class RubricNewsListWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'newsList';
    /**
     * @var int $limit максимальное количество выводимых новостей.
     * Если не указано, выводятся все новости.
     */
    public $limit;
    /**
     * @var array|NewsRubric[]|NewsRubric|null $rubrics рубрика, список новостных рубрик или GUID, из которых выводятся новости.
     * Если не указаны, то новости выводятся из всех рубрик
     */
    public $rubrics;

    /**
     * @var NewsModule $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $newsApi API модуля "Новости"
     */
    public function __construct(NewsModule $newsApi)
    {
        $this->api = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $rubrics = (array) $this->rubrics;

        foreach ($rubrics as &$rubric) {
            if (is_string($rubric)) {
                $rubric = $this->api->rubric()->get($rubric);
            }

            if (isset($rubric) && !$rubric instanceof NewsRubric) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param} should be instance of "{class}".',
                        [
                            'param' => 'rubrics',
                            'class' => 'NewsRubric'
                        ]
                    )
                );
            }
        }

        return $this->createResult(
            $this->template,
            [
                'news' => $this->api->getNewsByRubrics($rubrics, $this->limit)
            ]
        );
    }
}
 