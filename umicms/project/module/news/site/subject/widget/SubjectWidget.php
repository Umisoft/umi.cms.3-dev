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
use umicms\project\module\news\api\NewsModule;
use umicms\hmvc\widget\BaseWidget;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Виджет вывода сюжета новостей
 */
class SubjectWidget extends BaseWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|NewsSubject $subject сюжет или GUID
     */
    public $subject;

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
        if (is_string($this->subject)) {
            $this->subject = $this->api->rubric()->get($this->subject);
        }

        if (isset($this->subject) && !$this->subject instanceof NewsSubject) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'subject',
                        'class' => 'NewsSubject'
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'subject' => $this->subject
            ]
        );
    }
}
 