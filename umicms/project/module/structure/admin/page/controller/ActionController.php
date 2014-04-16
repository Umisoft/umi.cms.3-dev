<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\admin\page\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umi\orm\object\IObject;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\service\api\object\Backup;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\module\structure\api\object\StructureElement;

/**
 * Контроллер операций.
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var StructureModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param StructureModule $api
     */
    public function __construct(StructureModule $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['form', 'backups', 'backup'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return ['move'];
    }

    /**
     * Возвращает форму для объектного типа коллекции.
     * @throws HttpException
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');

        if ($collectionName != $this->api->element()->getName()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot use requested collection.');
        }

        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->api->element()->getForm($typeName, $formName);
    }

    protected function actionMove()
    {
        $data = $this->getIncomingData();

        if (!isset($data['object'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Cannot get object to move.');
        }

        $object = $this->api->element()->getById($data['object'][IObject::FIELD_IDENTIFY]);
        $object->setVersion($data['object'][IObject::FIELD_VERSION]);

        if (isset($data['branch'])) {
            $branch = $this->api->element()->getById($data['branch'][IObject::FIELD_IDENTIFY]);
            $branch->setVersion($data['branch'][IObject::FIELD_VERSION]);
        } else {
            $branch = null;
        }

        if (isset($data['sibling'])) {
            $previousSibling = $this->api->element()->getById($data['sibling'][IObject::FIELD_IDENTIFY]);
            $previousSibling->setVersion($data['sibling'][IObject::FIELD_VERSION]);
        } else {
            $previousSibling = null;
        }

        $this->api->element()->move($object, $branch, $previousSibling);

        return '';
    }

    /**
     * Возвращает список резервных копий
     * @return Backup[]
     */
    protected function actionBackups()
    {
        $elementId = $this->getRequiredQueryVar('id');

        return $this->api->element()->getBackupList(
            $this->api->element()->getById($elementId)
        );
    }

    /**
     * Возвращает резервную копию
     * @return StructureElement
     */
    protected function actionBackup()
    {
        $elementId = $this->getRequiredQueryVar('id');
        $backupId = $this->getRequiredQueryVar('backupId');
        $element = $this->api->element()->getById($elementId);

        return $this->api->element()->wakeUpBackup($element, $backupId);
    }

}
