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
     * @var array $api конфигурация API модуля
     */
    public $api = [];
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
 