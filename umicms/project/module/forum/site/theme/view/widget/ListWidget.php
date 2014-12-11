<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\site\theme\view\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseListWidget;
use umicms\project\module\forum\model\ForumModule;
use umicms\project\module\forum\model\object\ForumConference;

/**
 * Виджет для вывода списка тем конференции.
 */
class ListWidget extends BaseListWidget
{
    /**
     * @var ForumConference|string $conference GUID конференции или конференция, из которой выводятся темы
     */
    public $conference;
    /**
     * @var ForumModule $module модуль "Форум"
     */
    protected $module;

    /**
     * Конструктор.
     * @param ForumModule $module модуль "Форум"
     */
    public function __construct(ForumModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSelector()
    {
        if (is_string($this->conference)) {
            $this->conference = $this->module->conference()->get($this->conference);
        }

        if (isset($this->conference) && !$this->conference instanceof ForumConference) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'conference',
                        'class' => ForumConference::className()
                    ]
                )
            );
        }

        return $this->module->getTheme($this->conference);
    }
}
 