<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\controller;

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormSimpleController;
use umicms\project\module\blog\model\object\PostStatus;

/**
 * Контроллер помещения поста блога в черновики.
 */
class ToDraftController extends BaseCmsController
{
    use TFormSimpleController;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var BlogPost $blogPost пост блога
     */
    protected $blogPost;

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
    protected function buildForm()
    {
        $this->blogPost = $this->module->post()->getById($this->getRouteVar('id'));

        if (!$this->isAllowed($this->blogPost, 'draft')) {
            throw new ResourceAccessForbiddenException(
                $this->blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->module->post()->getForm(BlogPost::FORM_DRAFT_POST, $this->blogPost->getTypeName());
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->blogPost->status = $this->module->postStatus()->get(PostStatus::GUID_DRAFT);
        $this->commit();
    }
}
 