<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module;

use umi\config\entity\IConfig;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RequiredDependencyException;
use umicms\module\toolbox\ModuleTools;

/**
 * Трейт для работы с модулями.
 */
trait TModuleAware
{
    /**
     * @var ModuleTools $traitModuleTools
     */
    private $traitModuleTools;
    /**
     * @var array $traitModuleClasses список классов модулей в формате [$moduleName => $moduleClassName, ...]
     */
    private $traitModuleClasses;

    /**
     * @see IModuleAware::setModuleTools()
     */
    public function setModuleTools(ModuleTools $moduleTools)
    {
        $this->traitModuleTools = $moduleTools;
    }

    /**
     * Возвращает модуль по имени класса.
     * @param string $moduleClassName
     * @return BaseModule
     */
    protected function getModule($moduleClassName)
    {
        return $this->getModuleTools()->getService('umicms\module\BaseModule', $moduleClassName);
    }

    /**
     * Возвращает модуль по имени.
     * @param string $moduleName
     * @throws NonexistentEntityException если модуля с заданным именем нет
     * @return BaseModule
     */
    protected function getModuleByName($moduleName)
    {
        if (!isset($this->getModuleNames()[$moduleName])) {
            throw new NonexistentEntityException(
                sprintf('Module "%s" does not exist.', $moduleName)
            );
        }

        return $this->getModule($this->getModuleNames()[$moduleName]);
    }

    /**
     * Возвращает список имен зарегестрированных модулей.
     * @return array
     */
    protected function getModuleNames()
    {
        return array_keys($this->getModuleClassesByName());
    }

    /**
     * Возвращает имена классов модулей по именам.
     * @return array
     */
    protected function getModuleClassesByName()
    {
        if (is_null($this->traitModuleClasses)) {
            $moduleClasses = [];

            /**
             * @var IConfig $config
             */
            foreach ($this->getModuleTools()->modules as $moduleClass => $config) {
                if ($config->has('name')) {
                    $moduleClass = $config->has('className') ? $config->get('className') : $moduleClass;
                    $moduleClasses[$config->get('name')] = $moduleClass;
                }
            }

            $this->traitModuleClasses = $moduleClasses;
        }

        return $this->traitModuleClasses;
    }

    /**
     * Возвращает инструментарий для работы с модулями.
     * @throws RequiredDependencyException если инструментарий не был внедрен
     * @return ModuleTools
     */
    private function getModuleTools()
    {
        if (!$this->traitModuleTools) {
            throw new RequiredDependencyException(sprintf(
                'Module Tools are not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitModuleTools;
    }
}
 