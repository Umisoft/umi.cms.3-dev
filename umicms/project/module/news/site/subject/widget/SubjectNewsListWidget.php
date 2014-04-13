<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\site\subject\widget;

use umicms\exception\InvalidArgumentException;
use umicms\project\module\news\api\NewsApi;
use umicms\hmvc\widget\BaseWidget;
use umicms\project\module\news\api\object\NewsSubject;

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
     * @var array|NewsSubject[]|NewsSubject|null $subjects список GUID новостных сюжетов, из которых выводятся новости.
     * Если не указаны, то выводятся новости всех сюжетов
     */
    public $subjects = [];

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
        $subjects = (array) $this->subjects;

        foreach ($subjects as &$subject) {
            if (is_string($subject)) {
                $subject = $this->api->rubric()->get($subject);
            }

            if (isset($subject) && !$subject instanceof NewsSubject) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param} should be instance of "{class}".',
                        [
                            'param' => 'subjects',
                            'class' => 'NewsSubject'
                        ]
                    )
                );
            }
        }

        return $this->createResult(
            $this->template,
            [
                'news' => $this->api->getSubjectNews($subjects, $this->limit)
            ]
        );
    }
}
 