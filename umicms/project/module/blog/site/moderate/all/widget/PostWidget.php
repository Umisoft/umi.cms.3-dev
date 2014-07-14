<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\all\widget;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;

/**
 * Виджет вывода поста, требующего модерации.
 */
class PostWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'page';
    /**
     * @var string|BlogPost $blogPost пост или GUID поста требующего модерации
     */
    public $blogPost;

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
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> BlogPost $blogPost пост блога</li>
     * </ul>
     * @throws InvalidArgumentException
     * @throws ResourceAccessForbiddenException
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->module->post()->getNeedModeratePost($this->blogPost);
        }

        if (!$this->blogPost instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogPost',
                        'class' => BlogPost::className()
                    ]
                )
            );
        }

        if (!$this->isAllowed($this->blogPost)) {
            throw new ResourceAccessForbiddenException(
                $this->blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->createResult(
            $this->template,
            [
                'blogPost' => $this->blogPost
            ]
        );
    }
}
 