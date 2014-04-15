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
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Виджет для вывода URL на RSS-ленту по сюжету.
 */
class SubjectNewsRssUrlWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'rssLink';

    /**
     * @var string|NewsSubject $subject новостной сюжет или GUID сюжета, по которым формируется RSS-лента.
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
            $this->subject = $this->api->subject()->get($this->subject);
        }

        if (isset($this->subject) && !$this->subject instanceof NewsSubject) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param} should be instance of "{class}".',
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
                'url' => $this->getUrl('rss', ['slug' => $this->subject->slug])
            ]
        );
    }
}
 