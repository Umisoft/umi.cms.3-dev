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

use umi\acl\IAclResource;
use umi\orm\metadata\IObjectType;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Виджет отправки поста на модерацию.
 */
class SendToModerationWidget extends BaseFormWidget implements IAclResource
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
        if (is_string($this->blogDraft)) {
            $this->blogDraft = $this->api->post()->getDraft($this->blogDraft);
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

        $form = $this->api->post()->getForm(
            BlogPost::FORM_MODERATE_POST,
            IObjectType::BASE,
            $this->blogDraft
        );

        $form->setAction($this->getUrl('sendToModeration', ['id' => $this->blogDraft->getId()]));

        return $form;
    }
}
 