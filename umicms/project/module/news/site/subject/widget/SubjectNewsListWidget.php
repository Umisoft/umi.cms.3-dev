<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\subject\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsSubject;

/**
 * Виджет для вывода списка новостей по сюжетам.
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
        $subjects = (array) $this->subjects;

        foreach ($subjects as &$subject) {
            if (is_string($subject)) {
                $subject = $this->module->rubric()->get($subject);
            }

            if (!$subject instanceof NewsSubject) {
                throw new InvalidArgumentException(
                    $this->translate(
                        'Widget parameter "{param}" should be instance of "{class}".',
                        [
                            'param' => 'subjects',
                            'class' => NewsSubject::className()
                        ]
                    )
                );
            }
        }

        return $this->module->getNewsBySubjects($subjects);
    }
}
 