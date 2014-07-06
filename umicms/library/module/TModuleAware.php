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
     * @see IModuleAware::setModuleTools()
     */
    public function setModuleTools(ModuleTools $moduleTools)
    {
        $this->traitModuleTools = $moduleTools;
    }

    /**
     * Возвращает модуль по имени класса.
     * @param string $className
     * @return IModule
     */
    protected function getModuleByClass($className)
    {
        return $this->getModuleTools()->getModuleByClass($className);
    }

    /**
     * Возвращает список всех модулей.
     * @return IModule[]
     */
    protected function getModules()
    {
        return $this->getModuleTools()->getModules();
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
 