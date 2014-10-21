<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\reject\widget;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogPost;

/**
 * Виджет отправки поста на модерацию.
 */
class SendToModerationFormWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'sendToModerationForm';
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = self::REFERER_REDIRECT;
    /**
     * @var string|BlogPost $blogPost пост или GUID поста отправляемого на модерацию
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
            $this->blogPost = $this->module->post()->getRejectedPost($this->blogPost);
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

        $form = $this->module->post()->getForm(
            BlogPost::FORM_MODERATE_POST,
            $this->blogPost->getTypeName(),
            $this->blogPost
        );

        $form->setAction($this->getUrl('sendToModeration', ['id' => $this->blogPost->getId()]));

        return $form;
    }
}
 