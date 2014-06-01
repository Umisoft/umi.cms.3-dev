<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\toolbox\factory;

use umi\config\entity\IConfig;
use umi\toolkit\factory\IFactory;
use umi\toolkit\factory\TFactory;
use umicms\model\Model;
use umicms\model\ModelCollection;
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
 