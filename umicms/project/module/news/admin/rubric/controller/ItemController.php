<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\controller\BaseRestItemController;
use umicms\project\admin\api\controller\TTrashController;
use umicms\project\module\news\api\NewsPublicApi;
use umicms\project\module\news\object\NewsRubric;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ItemController extends BaseRestItemController
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
    protected function get()
    {
        $id = $this->getRouteVar('id');
        return $this->api->rubric()->getById($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function update(ICmsObject $object, array $data)
    {
        if (!isset($data[NewsRubric::FIELD_VERSION])) {
            throw new RuntimeException('Cannot save object. Object version is unknown');
        }

        $object->setVersion($data[NewsRubric::FIELD_VERSION]);

        // TODO: forms
        foreach ($data as $propertyName => $value) {
            if ($object->hasProperty($propertyName)
                && !$object->getProperty($propertyName)->getIsReadOnly()
                && !is_array($value)

            ) {
                $object->setValue($propertyName, $value);
            }
        }

        $this->getObjectPersister()->commit();

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ICmsObject $object)
    {
        if ($object instanceof NewsRubric) {
            $this->api->rubric()->delete($object);
            $this->getObjectPersister()->commit();
        }
    }
}
