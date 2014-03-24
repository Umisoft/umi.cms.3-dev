<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\rubric\widget;

use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\news\api\NewsApi;

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
     * @var array $rubricGuids список GUID новостных рубрик, из которых выводятся новости.
     * Если не указаны, то новости выводятся из всех рубрик
     */
    public $rubricGuids = [];

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
        return $this->createResult(
            $this->template,
            [
                'news' => $this->api->getRubricNews($this->rubricGuids, $this->limit)
            ]
        );
    }
}
 