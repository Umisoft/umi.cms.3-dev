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
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Виджет для вывода списка новостей по сюжетам
 */
class SubjectNewsListWidget extends BaseListWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'newsList';
    /**
     * @var array|NewsSubject[]|NewsSubject|null $subjects сюжет, новостных сюжетов или GUID, из которых выводятся новости.
     * Если не указаны, то выводятся новости всех сюжетов
     */
    public $subjects = [];

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
    protected function getSelector()
    {
        $subjects = (array) $this->subjects;

        foreach ($subjects as &$subject) {
            if (is_string($subject)) {
                $subject = $this->api->rubric()->get($subject);
            }

            if (isset($subject) && !$subject instanceof NewsSubject) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'subjects',
                            'class' => 'NewsSubject'
                        ]
                    )
                );
            }
        }

        return $this->api->getNewsBySubjects($subjects);
    }
}
 