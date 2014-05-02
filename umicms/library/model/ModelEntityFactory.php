<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model;

use umi\config\entity\IConfig;
use umi\toolkit\factory\IFactory;
use umi\toolkit\factory\TFactory;
use umicms\model\scheme\TableSchemeLoader;

/**
 * Фабрика сущностей моделей данных
 */
class ModelEntityFactory implements IFactory
{
    use TFactory;

    /**
     * @var string $modelCollectionClass класс коллекции моделей
     */
    public $modelCollectionClass = 'umicms\model\ModelCollection';
    /**
     * @var string $modelClass класс модели
     */
    public $modelClass = 'umicms\model\Model';
    /**
     * @var string $tableSchemeLoaderClass класс загрузчика схемы таблицы из конфигурации
     */
    public $tableSchemeLoaderClass = 'umicms\model\scheme\TableSchemeLoader';

    /**
     * Создает коллекцию моделей.
     * @param array $config конфигурация
     * @return ModelCollection
     */
    public function createModelCollection(array $config)
    {
        return $this->getPrototype(
            $this->modelCollectionClass,
            ['umicms\model\ModelCollection']
        )
            ->createInstance([$config]);
    }

    /**
     * Создает модель данных.
     * @param string $modelName имя модели
     * @param IConfig $config конфигурация
     * @return Model
     */
    public function createModel($modelName, IConfig $config)
    {
        return $this->getPrototype(
            $this->modelClass,
            ['umicms\model\Model']
        )
            ->createInstance([$modelName, $config]);
    }

    /**
     * Возвращает загрузчик схемы таблицы из конфигурации.
     * @return TableSchemeLoader
     */
    public function getTableSchemeLoader()
    {
        return $this->getPrototype(
            $this->tableSchemeLoaderClass,
            ['umicms\model\scheme\TableSchemeLoader']
        )
            ->createSingleInstance();
    }
}
 