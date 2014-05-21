<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
































    /**
     * Возвращает модуль по имени.
     * @param string $moduleName
     * @throws NonexistentEntityException если модуля с заданным именем нет
     * @return IModule
     */
    /* public function getModule($moduleName)
     {
         if (!in_array($moduleName, $this->getModuleNames())) {
             throw new NonexistentEntityException(
                 sprintf('Module "%s" does not exist.', $moduleName)
             );
         }

         return $this->getModuleByClass();
     }*/

    /**
     * Возвращает список имен зарегестрированных модулей.
     * @return array
     */
    /*public function getModuleNames()
    {
        return array_keys($this->getModuleClassesByName());
    }*/

    /**
     * Возвращает имена классов модулей по именам.
     * @return array
     */
    /*protected function getModuleClassesByName()
    {
        if (is_null($this->moduleClasses)) {
            $moduleClasses = [];

            foreach ($this->modules as $moduleClass => $config) {
                if ($config->has('name')) {
                    $moduleClass = $config->has('className') ? $config->get('className') : $moduleClass;
                    $moduleClasses[$config->get('name')] = $moduleClass;
                }
            }

            $this->moduleClasses = $moduleClasses;
        }

        return $this->moduleClasses;
    }*/

}
