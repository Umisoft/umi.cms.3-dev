<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umi\orm\object\IObject;
use umicms\project\admin\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{

    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsPublicApi $api
     */
    public function __construct(NewsPublicApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueryActions()
    {
        return ['settings', 'form'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModifyActions()
    {
        return ['move'];
    }

    /**
     * Возвращает форму.
     */
    protected function actionForm()
    {
        // TODO: add form
        return $this->api->rubric()->getCollection()->getMetadata()->getBaseType();
    }

    protected function actionMove()
    {

        if ($objectInfo = $this->getPostVar('object')) {
            $object = $this->api->rubric()->getById($objectInfo[IObject::FIELD_IDENTIFY]);
            $object->setVersion($objectInfo[$objectInfo[IObject::FIELD_VERSION]]);
        } else {
            $object = null;
        }

        if ($branchInfo = $this->getPostVar('branch')) {
            $branch = $this->api->rubric()->getById($branchInfo[IObject::FIELD_IDENTIFY]);
            $branch->setVersion($objectInfo[$branchInfo[IObject::FIELD_VERSION]]);
        } else {
            $branch = null;
        }

        if ($previousSiblingInfo = $this->getPostVar('sibling')) {
            $previousSibling = $this->api->rubric()->getById($previousSiblingInfo[IObject::FIELD_IDENTIFY]);
            $previousSibling->setVersion($previousSiblingInfo[$objectInfo[IObject::FIELD_VERSION]]);
        } else {
            $previousSibling = null;
        }


        $this->api->rubric()->getCollection()->move($object, $branch, $previousSibling);

        return '';
    }


}
 