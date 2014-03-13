<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject\widget;

use umicms\project\module\news\api\NewsApi;
use umicms\hmvc\widget\BaseWidget;

/**
 * Виджет для вывода списка новостей по сюжетам
 */
class SubjectNewsListWidget extends BaseWidget
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
     * @var array $rubricGuids список GUID новостных сюжетов, из которых выводятся новости.
     * Если не указаны, то выводятся новости всех сюжетов
     */
    public $subjectGuids = [];

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
                'news' => $this->api->getSubjectNews($this->subjectGuids, $this->limit)
            ]
        );
    }
}
 