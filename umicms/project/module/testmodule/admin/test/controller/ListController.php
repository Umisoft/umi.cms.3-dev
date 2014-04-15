<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\admin\test\controller;

use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\testmodule\api\TestRepository;

class ListController extends BaseRestListController
{
    /**
     * @var TestRepository $testRepository
     */
    protected $testRepository;

    /**
     * Конструктор.
     * @param TestRepository $testRepository
     */
    public function __construct(TestRepository $testRepository)
    {
        $this->testRepository = $testRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->testRepository->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->testRepository->select();
    }

    /**
     * {@inheritdoc}
     */
    protected function create(array $data)
    {
        // TODO: forms
        $object = $this->testRepository->add();

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
}