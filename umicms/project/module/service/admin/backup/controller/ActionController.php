<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\service\admin\backup\controller;

use umicms\orm\selector\CmsSelector;
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\module\service\api\BackupRepository;

/**
 * Контроллер действий над списком.
 */
class ActionController extends BaseRestActionController
{
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
    public function getQueryActions()
    {
        return ['list'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

    /**
     * Возвращает список всех резервных копий.
     * @return CmsSelector
     */
    protected function actionList()
    {
        return $this->backupRepository->select();
    }
}
 