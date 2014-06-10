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
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер добавления поста
 */
class PostAddController extends BaseAccessRestrictedController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;
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
     * @param BlogModule $blogModule API модуля "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->api = $blogModule;
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
            $blogCategory = $this->api->category()->getById($blogCategoryId);
        }

        if (!$blogCategory instanceof BlogCategory) {
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

        $this->blogPost = $this->api->post()->add();
        $this->blogPost->category = $blogCategory;

        if (!$this->isAllowed($this->blogPost)) {
            throw new ResourceAccessForbiddenException(
                $this->blogPost,
                $this->translate('Access denied')
            );
        }

        return $this->api->post()->getForm(
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
        $this->getObjectPersister()->commit();
        $this->added = true;
    }

    protected function buildResponseContent()
    {
        return [
            'added' => $this->added,
            'blogPost' => $this->blogPost
        ];
    }
}
 