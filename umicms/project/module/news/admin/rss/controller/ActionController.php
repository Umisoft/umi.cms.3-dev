<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rss\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsApi;

/**
 * Контроллер операций.
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var NewsApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsApi $api
     */
    public function __construct(NewsApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['form', 'import'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');

        if ($collectionName != $this->api->rss()->collectionName) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->rss()->getCollection()->getForm($typeName, $formName);
    }

    /**
     * Запускает импорт RSS-ленты.
     */
    protected function actionImport()
    {
        $importRssId = $this->getRequiredQueryVar('importRssId');

        $guidItemRss = $this->api->rss()->getById($importRssId);

        $this->api->importRss($guidItemRss);
        $this->getObjectPersister()->commit();
    }
}
