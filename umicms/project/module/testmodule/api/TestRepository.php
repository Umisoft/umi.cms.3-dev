<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\api;

use umi\orm\manager\IObjectManagerAware;
use umi\orm\manager\TObjectManagerAware;
use umicms\api\IPublicApi;
use umicms\api\repository\BaseObjectRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\testmodule\api\object\Test;

/**
 * Репозиторий тестовый.
 */
class TestRepository extends BaseObjectRepository implements IPublicApi, IObjectManagerAware
{
    use TObjectManagerAware;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'testTest';

    /**
     * Возвращает селектор для выбора бэкапов.
     * @return CmsSelector
     */
    public function select()
    {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает элемент по GUID.
     * @param $guid
     * @throws NonexistentEntityException
     * @return Test
     */
    public function getByGuid($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает элемент по Id.
     * @param int $id
     * @throws NonexistentEntityException
     * @return Test
     */
    public function getById($id)
    {
        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет новость.
     * @return Test
     */
    public function add()
    {
        return $this->getCollection()->add();
    }
}
 