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

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogComment;


/**
 * Виджет снятия комментария с спубликации.
 */
class UnpublishFormWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'unpublishForm';
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = self::REFERER_REDIRECT;
    /**
     * @var string|BlogComment $blogComment комментарий или GUID комментария
     */
    public $blogComment;
    /**
     * @var BlogModule $api модуль "Блоги"
     */
    protected $model;

    /**
     * Конструктор.
     * @param BlogModule $blogModule модуль "Блоги"
     */
    public function __construct(BlogModule $blogModule)
    {
        $this->model = $blogModule;
    }

    /**
     * {@inheritdoc}
     */
    protected function getForm()
    {
        if (is_string($this->blogComment)) {
            $this->blogComment = $this->model->comment()->get($this->blogComment);
        }

        if (!$this->blogComment instanceof BlogComment) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'blogComment',
                        'class' => BlogComment::className()
                    ]
                )
            );
        }

        if (!$this->isAllowed($this->blogComment)) {
            throw new ResourceAccessForbiddenException(
                $this->blogComment,
                $this->translate('Access denied')
            );
        }

        $form = $this->model->comment()->getForm(
            BlogComment::FORM_UNPUBLISH_COMMENT,
            BlogComment::TYPE,
            $this->blogComment
        );

        $form->setAction($this->getUrl('unpublish', ['id' => $this->blogComment->getId()]));

        return $form;
    }
}
 