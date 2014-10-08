<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\comment\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseTreeWidget;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\module\blog\model\object\CommentStatus;

/**
 * Виджет для вывода списка комментариев.
 */
class ListWidget extends BaseTreeWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'list';
    /**
     * @var string $orderBy имя поля, по которому происходит сортировка потомков одного уровня
     */
    public $orderBy = BlogComment::FIELD_PUBLISH_TIME;
    /**
     * @var string $direction направление, по которому происходит сортировка потомков одного уровня
     */
    public $direction = CmsSelector::ORDER_DESC;
    /**
     * @var string|BlogPost $blogPost GUID или пост блога, к которому необходимо вывести комментарии
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
     * {@inheritdoc}
     */
    protected function getCollection()
    {
        return $this->module->comment();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSelector(CmsSelector $selector)
    {
        if (is_string($this->blogPost)) {
            $this->blogPost = $this->module->post()->get($this->blogPost);
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

        $selector->where(BlogComment::FIELD_POST)->equals($this->blogPost);

        if ($this->isAllowed($selector->getCollection(), 'getCommentsWithNeedModeration')) {
            $statusGuids = [
                CommentStatus::GUID_PUBLISHED,
                CommentStatus::GUID_NEED_MODERATION,
                CommentStatus::GUID_UNPUBLISHED
            ];

        } else {
            $statusGuids = [
                CommentStatus::GUID_PUBLISHED,
                CommentStatus::GUID_UNPUBLISHED
            ];
        }

        $selector->where(BlogComment::FIELD_STATUS . '.' . CommentStatus::FIELD_GUID)->in($statusGuids);

    }

}
 