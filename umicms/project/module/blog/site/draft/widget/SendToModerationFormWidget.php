<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\draft\widget;

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
     * @var string|BlogPost $blogDraft черновик или GUID черновика отправляемого на модерацию
     */
    public $blogDraft;
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
        if (is_string($this->blogDraft)) {
            $this->blogDraft = $this->module->post()->getDraft($this->blogDraft);
        }

        if (!$this->blogDraft instanceof BlogPost) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogDraft',
                        'class' => BlogPost::className()
                    ]
                )
            );
        }

        if (!$this->isAllowed($this->blogDraft)) {
            throw new ResourceAccessForbiddenException(
                $this->blogDraft,
                $this->translate('Access denied')
            );
        }

        $form = $this->module->post()->getForm(
            BlogPost::FORM_MODERATE_POST,
            $this->blogDraft->getTypeName(),
            $this->blogDraft
        );

        $form->setAction($this->getUrl('sendToModeration', ['id' => $this->blogDraft->getId()]));

        return $form;
    }
}
 