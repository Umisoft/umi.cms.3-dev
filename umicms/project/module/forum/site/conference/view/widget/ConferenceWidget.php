<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\conference\view\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\forum\model\ForumModule;
use umicms\project\module\forum\model\object\ForumConference;

/**
 * Виджет для вывода конференции.
 */
class ConferenceWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|ForumConference $forumConference GUID конференции форума или конференция
     */
    public $forumConference;
    /**
     * @var ForumModule $module модуль "Форум"
     */
    protected $module;

    public function __construct(ForumModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\forum\model\object\ForumConference $forumConference конференция форума
     *
     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->forumConference)) {
            $this->forumConference = $this->module->conference()->get($this->forumConference);
        }

        if (!$this->forumConference instanceof ForumConference) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'forumConference',
                        'class' => ForumConference::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'forumConference' => $this->forumConference
            ]
        );
    }
}
 