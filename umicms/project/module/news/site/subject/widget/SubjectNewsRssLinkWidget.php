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
use umicms\hmvc\widget\BaseLinkWidget;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsSubject;

/**
 * Виджет для вывода ссылки на RSS-ленту по сюжету.
 */
class SubjectNewsRssLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $template = 'rssLink';

    /**
     * @var string|NewsSubject $subject новостной сюжет или GUID сюжета, по которым формируется RSS-лента.
     */
    public $subject;

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
    protected function getLinkUrl()
    {
        if (is_string($this->subject)) {
            $this->subject = $this->module->subject()->get($this->subject);
        }

        if (!$this->subject instanceof NewsSubject) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'subject',
                        'class' => NewsSubject::className()
                    ]
                )
            );
        }

        return $this->getUrl('rss', ['slug' => $this->subject->slug]);
    }
}
 