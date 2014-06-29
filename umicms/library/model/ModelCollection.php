<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;
use umi\dbal\cluster\IDbClusterAware;
use umi\dbal\cluster\TDbClusterAware;
use umi\dbal\driver\IDialect;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\exception\NonexistentEntityException;
use umicms\exception\UnexpectedValueException;
use umicms\model\manager\IModelManagerAware;
use umicms\model\manager\TModelManagerAware;

/**
 * API для управления моделями данных
 */
class ModelCollection implements ILocalizable, IModelEntityFactoryAware, IModelManagerAware, IDbClusterAware
{
    use TLocalizable;
    use TModelEntityFactoryAware;
    use TModelManagerAware;
    use TConfigSupport;
    use TDbClusterAware;

    /**
     * Имя файла конфигурации схемы таблицы
     */
    const MODEL_SCHEME_CONFIG = 'scheme.config.php';
    /**
     * Имя файла конфигурации метаданных коллекции
     */
    const MODEL_METADATA_CONFIG = 'metadata.config.php';
    /**
     * Имя файла конфигурации коллекции
     */
    const MODEL_COLLECTION_CONFIG = 'collection.config.php';

    /**
     * @var Model[] $models список моделей
     */
    protected $models;
    /**
     * @var array $modelsConfig конфигурация моделей данных
     */
    private $modelsConfig;

    /**
     * Конструктор.
     * @param array $modelsConfig конфигурация моделей
     */
    public function __construct(array $modelsConfig)
    {
        $this->modelsConfig = $modelsConfig;
    }

    /**
     * Помечает все модели как модифицированные.
     * @return $this
     */
    public function migrateAll()
    {

        $connection = $this->getDbCluster()->getMaster()->getConnection();
        /** @var IDialect $dialect */
        $dialect = $connection->getDatabasePlatform();

        $connection->exec($dialect->getDisableForeignKeysSQL());

        $synchronizer = new SingleDatabaseSynchronizer($connection);

        $tables = [];
        foreach ($this->getModels() as $model) {
            $tables[] = $model->getTableScheme();
        }

        $scheme = new Schema($tables);
        var_dump($synchronizer->getUpdateSchema($scheme, true));
        $synchronizer->updateSchema($scheme, true);
        exit;
       //

        return $this;
    }

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
        return isset($this->modelsConfig[$modelName]);
    }

    /**
     * Возвращает модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return Model
     */
    public function getModel($modelName)
    {
        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }

        list($schemeConfig, $metadataConfig, $collectionConfig) = $this->getModelResources($modelName);

        $model = $this->getModelEntityFactory()->createModel(
            $modelName,
            $schemeConfig,
            $metadataConfig,
            $collectionConfig
        );

        return $this->models[$modelName] = $model;

    }

    /**
     * Возвращает список всех моделей коллекции.
     * @return Model[]
     */
    public function getModels()
    {
        $result = [];
        foreach ($this->getModelNames() as $modelName) {
            $result[] = $this->getModel($modelName);
        }
        return $result;
    }

    /**
     * Возвращает ресурсы модели данных.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @throws UnexpectedValueException если конфигурация невалидная
     * @return array [$schemeConfigPath, $metadataConfigPath, $collectionConfigPath]
     */
    protected function getModelResources($modelName)
    {
        if (!$this->hasModel($modelName)) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Model "{modelName}" does not exist.',
                    ['modelName' => $modelName]
                )
            );
        }

        $resource = $this->modelsConfig[$modelName] . '/';

        return [
            $resource . self::MODEL_SCHEME_CONFIG,
            $resource . self::MODEL_METADATA_CONFIG,
            $resource . self::MODEL_COLLECTION_CONFIG
        ];
    }

}
 