<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\admin\backup\controller;

use umi\hmvc\exception\http\HttpNotFound;
use umicms\project\admin\api\controller\BaseRestListController;
use umicms\project\module\service\api\BackupRepository;

class ListController extends BaseRestListController
{
    /**
     * @var BackupRepository $backupRepository
     */
    protected $backupRepository;

    /**
     * Конструктор.
     * @param BackupRepository $backupRepository
     */
    public function __construct(BackupRepository $backupRepository)
    {
        $this->backupRepository = $backupRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->backupRepository->collectionName;
    }

    /**
     * {@inheritdoc}
     */
    protected function getList()
    {
        return $this->backupRepository->select();
    }

    /**
     * Резервные копии создаются автоматически.
     * @throws HttpNotFound
     */
    protected function create(array $data)
    {
        throw new HttpNotFound($this->translate(
            'Cannot create backup. Method is not supported.'
        ));
    }
}