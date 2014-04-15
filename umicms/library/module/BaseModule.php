<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\spl\config\TConfigSupport;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\model\IModelEntityFactoryAware;
use umicms\model\ModelCollection;
use umicms\model\TModelEntityFactoryAware;
use umicms\orm\collection\ICmsCollection;

/**
 * Модуль UMI.CMS. Расширяет API системы.
 */
abstract class BaseModule implements IModule, ICollectionManagerAware, IModelEntityFactoryAware, IToolkitAware, ILocalizable
{
    use TCollectionManagerAware;
    use TModelEntityFactoryAware;
    use TToolkitAware;
    use TConfigSupport;
    use TLocalizable;

    /**
     * @var string $name имя модуля
     */
    public $name;
    /**
     * @var array $models конфигурация моделей, обслуживаемых модулем
     */
    public $models = [];
    /**
     * @var array $api конфигурация API модуля
     */
    public $api = [];

    /**
     * @var ModelCollection $modelCollection
     */
    protected $modelCollection;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getModels()
    {
        if (is_null($this->modelCollection)) {
            $this->modelCollection = $this->getModelEntityFactory()->createModelCollection($this->models);
        }

        return $this->modelCollection;
    }

    /**
     * Возвращает коллекцию объектов модуля по имени
     * @param string $collectionName имя коллекции
     * @return ICmsCollection
     */
    protected function getCollection($collectionName)
    {
        return $this->getCollectionManager()->getCollection($collectionName);
    }

    /**
     * Возвращает API.
     * @param string $apiClassName имя класса
     * @return object
     */
    protected function getApi($apiClassName)
    {
        $config = $this->getApiConfig($apiClassName);

        $apiConcreteClassName = isset($config['className']) ? $config['className'] : $apiClassName;

        return $this->getToolkit()->getPrototype($apiConcreteClassName, [$apiClassName])
            ->createSingleInstance([], $config);
    }

    /**
     * Возвращает конфигурацию API.
     * @param string $apiClassName класс API
     * @return array
     */
    protected function getApiConfig($apiClassName)
    {
        return isset($this->api[$apiClassName]) ? $this->configToArray($this->api[$apiClassName], true) : [];
    }


}
 