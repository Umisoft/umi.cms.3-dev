<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\project\module\news\api\NewsPublicApi;
use umicms\base\widget\BaseWidget;

/**
 * Виджет для вывода списка новостных рубрик
 */
class RubricListWidget extends BaseWidget
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
     * @var string $parentGuid GUID новостной рубрики, из которой выводятся рубрики.
     * Если не указан, выводятся все рубрики
     */
    public $parentGuid;

    /**
     * @var NewsPublicApi $api API модуля "Новости"
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsPublicApi $newsApi API модуля "Новости"
     */
    public function __construct(NewsPublicApi $newsApi)
    {
        $this->api = $newsApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createResult(
            $this->template,
            [
                'rubrics' => $this->api->getRubrics($this->parentGuid, $this->limit)
            ]
        );
    }
}
 