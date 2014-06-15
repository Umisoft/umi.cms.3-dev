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
use umicms\project\module\news\model\NewsModule;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\news\model\object\NewsSubject;

/**
 * Виджет вывода сюжета новостей.
 */
class SubjectWidget extends BaseCmsWidget
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
    public function __invoke()
    {
        if (is_string($this->subject)) {
            $this->subject = $this->module->rubric()->get($this->subject);
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

        return $this->createResult(
            $this->template,
            [
                'subject' => $this->subject
            ]
        );
    }
}
 