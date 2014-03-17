<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\widget;

use umicms\project\module\news\api\NewsPublicApi;
use umicms\base\widget\BaseWidget;

/**
 * Виджет для вывода списка новостей по рубрикам
 */
class RubricNewsListWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'item/list';
    /**
     * @var int $limit максимальное количество выводимых новостей.
     * Если не указано, выводятся все новости.
     */
    public $limit;
    /**
     * @var array $rubricGuids список GUID новостных рубрик, из которых выводятся новости.
     * Если не указаны, то новости выводятся из всех рубрик
     */
    public $rubricGuids = [];

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
                'news' => $this->api->getRubricNews($this->rubricGuids, $this->limit)
            ]
        );
    }
}
 