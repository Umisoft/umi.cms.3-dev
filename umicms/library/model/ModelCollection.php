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

use Doctrine\DBAL\Platforms\AbstractPlatform;
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
     * Запускает миграцию схем.
     * @return $this
     */
    public function syncAllSchemes()
    {
        $connection = $this->getDbCluster()->getMaster()->getConnection();
        /** @var IDialect $dialect */
        $dialect = $connection->getDatabasePlatform();

        $connection->exec($dialect->getDisableForeignKeysSQL());

        $synchronizer = new SingleDatabaseSynchronizer($connection);



        $tables = [];
        foreach ($this->getList() as $model) {
            $tableScheme = $model->getTableScheme();
            $tables[$tableScheme->getName()] = $tableScheme;
        }

        $currentScheme = $connection->getSchemaManager()->createSchema();
        foreach ($currentScheme->getTables() as $table) {
            if (!isset($tables[$table->getName()])) {
                $tables[] = $table;
            }
        }

        $scheme = new Schema($tables, [], $connection->getSchemaManager()->createSchemaConfig());

        $synchronizer->updateSchema($scheme, true);

        return $this;
    }

    /**
     * Помечает все модели как модифицированные.
     * @return $this
     */
    public function installAllSchemes()
    {

        $connection = $this->getDbCluster()->getMaster()->getConnection();
        /** @var IDialect $dialect */
        $dialect = $connection->getDatabasePlatform();

        $connection->exec($dialect->getDisableForeignKeysSQL());

        foreach ($this->getList() as $model) {
            $table = $model->getTableScheme();
            $queries = $connection->getSchemaManager()->getDatabasePlatform()->getCreateTableSQL(
                $table,
                AbstractPlatform::CREATE_INDEXES | AbstractPlatform::CREATE_FOREIGNKEYS
            );
            foreach ($queries as $sql) {
                $connection->exec($sql);
            }
        }

        return $this;
    }

    /**
     * Возвращает список имен моделей данных.
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->modelsConfig);
    }

    /**
     * Проевряет, существует ли модель данных по имени.
     * @param string $modelName имя модели
     * @return bool
     */
    public function has($modelName)
    {
        return isset($this->modelsConfig[$modelName]);
    }

    /**
     * Возвращает модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return Model
     */
    public function get($modelName)
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
    public function getList()
    {
        $result = [];
        foreach ($this->getNames() as $modelName) {
            $result[] = $this->get($modelName);
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
        if (!$this->has($modelName)) {
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
 