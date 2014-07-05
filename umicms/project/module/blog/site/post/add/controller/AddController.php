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

/**
 * Контроллер добавления поста
 */
class AddController extends BaseSitePageController
{
    use TFormController;

    /**
     * @var BlogModule $module модуль "Блоги"
     */
    protected $module;
    /**
     * @var bool $added флаг указывающий на статус добавление поста
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
        return 'addPost';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $blogCategory = null;
        $blogCategoryId = $this->getRouteVar('id');

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

        $this->blogPost = $this->module->addPost();
        $this->blogPost->category = $blogCategory;

        return $this->module->post()->getForm(
            BlogPost::FORM_ADD_POST,
            IObjectType::BASE,
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
     * {@inheritdoc}
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
 