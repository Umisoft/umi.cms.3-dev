<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\add\controller;

use umi\form\IForm;
use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogCategory;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\blog\model\object\PostStatus;

/**
 * Контроллер добавления поста
 */
class AddController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var string $template имя шаблона, по которому выводится результат
     */
    public $template = 'addPost';
    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var bool $added флаг, указывающий на статус добавление поста
     */
    private $added = false;
    /**
     * @var BlogPost $blogPost добавляемый пост
     */
    private $blogPost;

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
    protected function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $blogCategory = null;
        $blogCategoryId = $this->getRouteVar('id');
        $type = $this->getRouteVar('type', IObjectType::BASE);

        if (!is_null($blogCategoryId)) {
            $blogCategory = $this->module->category()->getById($blogCategoryId);
        }

        if ($blogCategory && !$blogCategory instanceof BlogCategory) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogCategory',
                        'class' => BlogCategory::className()
                    ]
                )
            );
        }

        $this->blogPost = $this->module->addPost($type);
        $this->blogPost->category = $blogCategory;

        $this->blogPost->setStatus($this->module->postStatus()->get(PostStatus::GUID_NEED_MODERATION));

        return $this->module->post()->getForm(
            $this->module->isGuestAuthor() ? BlogPost::FORM_ADD_VISITOR_POST : BlogPost::FORM_ADD_POST,
            $type,
            $this->blogPost
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();
        $this->added = true;
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное сохранение изменений
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница добавления поста
     * @templateParam umicms\project\module\blog\model\object\BlogPost $blogPost созданный пост блога. Передается только, если пост был успешно добавлен
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        $result = [
            'added' => $this->added,
            'page' => $this->getCurrentPage()
        ];

        if ($this->added) {
            $result['blogPost'] = $this->blogPost;
        }

        return $result;
    }
}
 