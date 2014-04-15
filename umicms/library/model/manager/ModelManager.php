<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model\manager;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;
use umi\dbal\cluster\IDbClusterAware;
use umi\dbal\cluster\TDbClusterAware;
use umicms\exception\RuntimeException;
use umicms\model\Model;

/**
 * Менеджер моделей данных
 */
class ModelManager implements IDbClusterAware
{
    use TDbClusterAware;

    /**
     * @var Model[] $newModels новые модели
     */
    protected $newModels = [];
    /**
     * @var Model[] $deletedModels удаленные модели
     */
    protected $deletedModels = [];
    /**
     * @var Model[] $modifiedModels измененные модели
     */
    protected $modifiedModels = [];

    /**
     * Помечает модель как новую
     * @param Model $model
     * @return $this
     */
    public function markAsNew(Model $model)
    {
        $this->newModels[] = $model;

        return $this;
    }

    /**
     * Помечает модель как удаленную
     * @param Model $model
     * @return $this
     */
    public function markAsDeleted(Model $model)
    {
        $this->deletedModels[] = $model;

        return $this;
    }

    /**
     * Помечает модель как модифицированную
     * @param Model $model
     * @return $this
     */
    public function markAsModified(Model $model)
    {
        if (!in_array($model, $this->newModels)) {
            $this->modifiedModels[] = $model;
        }

        return $this;
    }

    /**
     * Применяет изменения конфигураций и миграции схем таблиц.
     * @return $this
     */
    public function persist()
    {
        //TODO
    }

    protected function applyConfigChanges()
    {

    }

    /**
     * Применят миграции таблиц моделей.
     * @throws RuntimeException если не удалось выполнить миграции
     */
    protected function applyDbMigrations()
    {
        $synchronizer = new SingleDatabaseSynchronizer(
            $this->getDbCluster()->getMaster()->getConnection()
        );

        try {
            $this->createModelSchemes($synchronizer);
            $this->updateModelSchemes($synchronizer);
            $this->dropModelSchemes($synchronizer);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot apply database migrations.', 0, $e);
        }
    }

    /**
     * Выполняет запросы на создание таблиц для моделей.
     * @param SingleDatabaseSynchronizer $synchronizer
     */
    private function createModelSchemes(SingleDatabaseSynchronizer $synchronizer)
    {
        $createTables = [];

        foreach ($this->newModels as $model) {
            $createTables[] = $model->getTableScheme();
        }

        $createScheme = new Schema($createTables);

        $synchronizer->createSchema($createScheme);
    }

    /**
     * Выполняет запросы на модификацию таблиц для моделей.
     * @param SingleDatabaseSynchronizer $synchronizer
     */
    private function updateModelSchemes(SingleDatabaseSynchronizer $synchronizer)
    {
        $updateTables = [];

        foreach ($this->modifiedModels as $model) {
            $updateTables[] = $model->getTableScheme();
        }

        $updateScheme = new Schema($updateTables);

        $synchronizer->updateSchema($updateScheme);
    }

    /**
     * Выполняет запросы на удаление таблиц для моделей.
     * @param SingleDatabaseSynchronizer $synchronizer
     */
    private function dropModelSchemes(SingleDatabaseSynchronizer $synchronizer)
    {
        $dropTables = [];

        foreach ($this->deletedModels as $model) {
            $dropTables[] = $model->getTableScheme();
        }

        $dropScheme = new Schema($dropTables);

        $synchronizer->dropSchema($dropScheme);
    }
}
 