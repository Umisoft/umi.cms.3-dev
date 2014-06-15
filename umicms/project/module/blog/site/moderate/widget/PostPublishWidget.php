<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\moderate\widget;

use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;

/**
 * Виджет публикации поста, требующего модерации.
 */
class PostPublishWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'publishForm';
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = self::REFERER_REDIRECT;
    /**
     * @var string|BlogPost $blogPost пост или GUID поста, тербующего модерации
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
    protected function getForm()
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

        $form = $this->module->post()->getForm(
            BlogPost::FORM_PUBLISH_POST,
            IObjectType::BASE,
            $this->blogPost
        );

        $form->setAction($this->getUrl('publish', ['id' => $this->blogPost->getId()]));

        return $form;
    }
}
 