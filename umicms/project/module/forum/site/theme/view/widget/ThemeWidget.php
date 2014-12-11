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
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\forum\model\ForumModule;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * Виджет для вывода тем конференции.
 */
class ThemeWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var ForumTheme $theme тема конференции или GUID
     */
    public $theme;

    /**
     * @var ForumModule $module модуль "Форум"
     */
    protected  $module;

    /**
     * @param ForumModule $module модуль "Форум"
     */
    public function __construct(ForumModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\forum\model\object\ForumTheme $theme тема конференции форума
     *
     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->theme)) {
            $this->theme = $this->module->theme()->get($this->theme);
        }

        if (!$this->theme instanceof ForumTheme) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'theme',
                        'class' => ForumTheme::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'theme' => $this->theme
            ]
        );
    }
}
 