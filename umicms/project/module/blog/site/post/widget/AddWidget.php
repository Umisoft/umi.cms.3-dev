<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\post\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет добавления поста.
 */
class AddWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'addPostForm';
    /**
     * @var string|BlogCategory $blogCategory рубрика или GUID родительской рубрики
     */
    public $blogCategory;
    /**
     * @var BlogModule $api API модуля "Блоги"
     */
    protected $api;

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
    protected function getForm()
    {
        if (is_string($this->blogCategory)) {
            $this->blogCategory = $this->api->category()->get($this->blogCategory);
        }

        if (!$this->blogCategory instanceof BlogCategory) {
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

        $post = $this->api->post()->add();
        $post->category = $this->blogCategory;

        $form = $this->api->post()->getForm(BlogPost::FORM_ADD_POST, IObjectType::BASE, $post);

        $form->setAction($this->getUrl('add'));

        return $form;
    }
}
 