<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\view\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogAuthor;

/**
 * Виджет вывода информации об авторе.
 */
class AuthorWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|BlogAuthor $blogAuthor GUID автора
     */
    public $blogAuthor;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;

    /**
     * Конструктор.
     * @param BlogModule $module модуль "Блоги"
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\blog\model\object\BlogAuthor $blogAuthor автор

     * @throws InvalidArgumentException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->blogAuthor)) {
            $this->blogAuthor = $this->module->author()->get($this->blogAuthor);
        }

        if (!$this->blogAuthor instanceof BlogAuthor) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogAuthor',
                        'class' => BlogAuthor::className()
                    ]
                )
            );
        }

        return $this->createResult(
            $this->template,
            [
                'blogAuthor' => $this->blogAuthor
            ]
        );
    }
}
 