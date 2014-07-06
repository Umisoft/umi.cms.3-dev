<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
        $this->applyDbMigrations();
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

        $tables = [];
        foreach ($this->modifiedModels as $model) {
            $tables[] = $model->getTableScheme();
        }

        $scheme = new Schema($tables);
        var_dump($synchronizer->getUpdateSchema($scheme, true));
        $synchronizer->updateSchema($scheme, true);
    }
}
 