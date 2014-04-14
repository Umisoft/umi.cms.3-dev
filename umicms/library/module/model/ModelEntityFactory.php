<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\model;

use umi\toolkit\factory\IFactory;
use umi\toolkit\factory\TFactory;

/**
 * Фабрика сущностей моделей данных
 */
class ModelEntityFactory implements IFactory
{
    use TFactory;

    /**
     * @var string $modelCollectionClass класс коллекции моделей
     */
    public $modelCollectionClass = 'umicms\module\model\ModelCollection';

    /**
     * Создает коллекцию моделей.
     * @param array $config конфигурация
     * @return ModelCollection
     */
    public function createModelCollection(array $config)
    {
        return $this->getPrototype(
            $this->modelCollectionClass,
            ['umicms\module\model\ModelCollection']
        )
            ->createInstance([$config]);
    }
}
 