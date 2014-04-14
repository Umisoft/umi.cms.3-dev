<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module;

use umicms\exception\RequiredDependencyException;
use umicms\module\toolbox\ModuleTools;

/**
 * Трейт для получения API
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
     * Возвращает Модуль по имени класса.
     * @param string $moduleClassName
     * @return BaseModule
     */
    protected function getModule($moduleClassName)
    {
        return $this->getModuleTools()->getService('umicms\module\BaseModule', $moduleClassName);
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
 