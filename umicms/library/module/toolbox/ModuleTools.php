<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\module\toolbox;

use umi\config\entity\IConfig;
use umi\toolkit\exception\UnsupportedServiceException;
use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\exception\NonexistentEntityException;
use umicms\exception\UnexpectedValueException;
use umicms\module\IModule;
use umicms\module\IModuleAware;

/**
 * Инструментарий для работы с модулями.
 */
class ModuleTools implements IToolbox
{
    /**
     * Имя набора инструментов
     */
    const NAME = 'module';

    use TToolbox;

    /**
     * @var array $modules конфигурация модулей
     */
    public $modules = [];
    /**
     * @var IModule[] $moduleInstances спсисок модулей в формате [$className => $module, ...]
     */
    private $moduleInstances = [];

    /**
     * {@inheritdoc}
     */
    public function getService($serviceInterfaceName, $concreteClassName)
    {
        switch ($serviceInterfaceName) {
            case 'umicms\module\IModule':
                return $this->getModuleByClass($concreteClassName);
        }

        throw new UnsupportedServiceException($this->translate(
            'Toolbox "{name}" does not support service "{interface}".',
            ['name' => self::NAME, 'interface' => $serviceInterfaceName]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof IModuleAware) {
            $object->setModuleTools($this);
        }
    }

    /**
     * Возвращает модуль.
     * @param string $className имя класса
     * @throws UnexpectedValueException при невалидной конфигурации модуля
     * @return IModule
     */
    public function getModuleByClass($className)
    {
        if (isset($this->moduleInstances[$className])) {
            return $this->moduleInstances[$className];
        }

        $config = $this->getModuleConfigByClass($className);
        $concreteClassName = $config->get('className') ? : $className;

        return $this->moduleInstances[$className] = $this->getPrototype($concreteClassName, [$className])
            ->createSingleInstance([], $this->configToArray($config));
    }

    /**
     * Возвращает список всех модулей.
     * @return IModule[]
     */
    public function getModules()
    {
        $result = [];
        foreach ($this->modules as $className => $config)
        {
            $result[] = $this->getModuleByClass($className);
        }

        return $result;
    }

    /**
     * Возвращает конфигурацию модуля по классу
     * @param string $className
     * @throws UnexpectedValueException если конфигурация невалидна
     * @throws NonexistentEntityException ксли модуль не зарегистрирован
     * @return IConfig
     */
    protected function getModuleConfigByClass($className)
    {
        if (!isset($this->modules[$className])) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Module with class "{class}" is not registered.',
                    ['class' => $className]
                )
            );
        }

        $config = $this->modules[$className];
        if (!$config instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Configuration for module "{class}" should be an array.',
                    ['class' => $className]
                )
            );
        }

        return $config;
    }

}
