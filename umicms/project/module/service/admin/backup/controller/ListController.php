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
use umicms\project\admin\api\controller\DefaultRestListController;
use umicms\project\module\service\api\collection\BackupCollection;

class ListController extends DefaultRestListController
{
    /**
     * @var BackupCollection $backupRepository
     */
    protected $backupRepository;

    /**
     * Конструктор.
     * @param BackupCollection $backupRepository
     */
    public function __construct(BackupCollection $backupRepository)
    {
        $this->backupRepository = $backupRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionName()
    {
        return $this->backupRepository->getName();
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