<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\model;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;
use umi\dbal\cluster\IDbClusterAware;
use umi\dbal\cluster\TDbClusterAware;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\AlreadyExistentEntityException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;

/**
 * API для управления моделями данных
 */
class ModelCollection implements IDbClusterAware, ILocalizable
{
    use TDbClusterAware;
    use TLocalizable;

    /**
     * Конструктор.
     * @param array $modelsConfig конфигурация моделей
     */
    public function __construct(array $modelsConfig)
    {
        $this->modelsConfig = $modelsConfig;
    }

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
     * @var Model[] $models список моделей
     */
    protected $models;
    /**
     * @var array $modelsConfig конфигурация моделей данных
     */
    private $modelsConfig;

    /**
     * Возвращает список имен моделей данных.
     * @return array
     */
    public function getModelNames()
    {
        return array_keys($this->modelsConfig);
    }

    /**
     * Проевряет, существует ли модель данных по имени.
     * @param string $modelName имя модели
     * @return bool
     */
    public function hasModel($modelName)
    {
        return isset($this->getModelNames()[$modelName]);
    }

    /**
     * Возвращает модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return Model
     */
    public function getModel($modelName)
    {
        if (!$this->hasModel($modelName)) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Model "{modelName}" does not exist.',
                    ['modelName' => $modelName]
                )
            );
        }

        //TODO
    }

    /**
     * Добавляет модель в группу. Если группы не существует, она будет создана.
     * @param string $groupName имя группы
     * @param string $modelName имя модели
     * @throws AlreadyExistentEntityException если модель с заданным именем существует
     * @return Model
     */
    public function addModel($groupName, $modelName)
    {
        //TODO
    }

    /**
     * Удаляет модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return $this
     */
    public function deleteModel($modelName)
    {
        //TODO
    }

    /**
     * Применяет изменения конфигураций и миграции схем таблиц.
     * @return $this
     */
    public function persist()
    {
        //TODO
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
 