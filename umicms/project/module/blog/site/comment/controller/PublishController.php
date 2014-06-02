<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\site\comment\controller;

use umi\form\IForm;
use umi\http\Response;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\exception\RuntimeException;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogComment;
use umicms\project\site\controller\TFormController;

/**
 * Контроллер публикации комментария.
 */
class PublishController extends BaseAccessRestrictedController implements IObjectPersisterAware
{
    use TFormController;
    use TObjectPersisterAware;

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
    protected function buildForm()
    {
        return $this->api->comment()->getForm(BlogComment::FORM_PUBLISH_COMMENT, BlogComment::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $blogComment = $this->api->comment()->getById($this->getRouteVar('id'));
        $blogComment->published();

        $this->getObjectPersister()->commit();
    }

    /**
     * Формирует ответ.
     * @throws RuntimeException
     * @return Response
     */
    protected function buildResponse()
    {
        if (count($this->errors)) {
            throw new RuntimeException($this->translate(
                'Invalid form.'
            ));
        }

        return $this->buildRedirectResponse();
    }
}
 