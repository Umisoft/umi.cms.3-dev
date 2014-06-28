<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @var array $submodules конфигурация подмодулей
     */
    public $submodules = [];
    /**
     * @var array $options настройки модуля
     */
    public $options = [];

    /**
     * @var ModelCollection $modelCollection
     */
    protected $modelCollection;

    /**
     * Возаращает имя класса модуля.
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

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
     * Возвращает подмодуль.
     * @param string $className имя класса
     * @return object
     */
    protected function getSubmodule($className)
    {
        $config = $this->getSubmoduleConfig($className);

        $concreteClassName = isset($config['className']) ? $config['className'] : $className;

        return $this->getToolkit()->getPrototype($concreteClassName, [$className])
            ->createSingleInstance([], $config);
    }

    /**
     * Возвращает конфигурацию подмодуля.
     * @param string $className класс модуля
     * @return array
     */
    protected function getSubmoduleConfig($className)
    {
        return isset($this->submodules[$className]) ? $this->configToArray($this->submodules[$className], true) : [];
    }

    /**
     * Возвращает значение настройки для модуля.
     * @param string $settingName имя настройки
     * @param mixed $defaultValue значение по умолчанию
     * @return mixed
     */
    protected function getSetting($settingName, $defaultValue = null) {
        if (isset($this->options[$settingName])) {
            return $this->options[$settingName];
        }

        return $defaultValue;
    }

}
 