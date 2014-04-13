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
use umicms\project\module\news\api\NewsApi;
use umicms\project\module\news\api\object\NewsRubric;

/**
 * Виджет для вывода списка новостных рубрик
 */
class RubricListWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * @var int $limit максимальное количество выводимых рубрик.
     * Если не указано, выводятся все рубрики.
     */
    public $limit;
    /**
     * @var string|null|NewsRubric $parentGuid новостная рубрика или GUID, из которой выводятся дочерние рубрики.
     * Если не указан, выводятся все корневые рубрики.
     */
    public $parentRubric;

    /**
     * @var NewsApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $newsApi API модуля "Новости"
     */
    public function __construct(NewsApi $newsApi)
    {
        $this->api = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        if (is_string($this->parentRubric)) {
            $this->parentRubric = $this->api->rubric()->get($this->parentRubric);
        }

        if (isset($this->parentRubric) && !$this->parentRubric instanceof NewsRubric) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param} should be instance of "{class}".',
                    [
                        'param' => 'parentRubric',
                        'class' => 'NewsRubric'
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'rubrics' => $this->api->getRubrics($this->parentRubric, $this->limit)
            ]
        );
    }
}
 