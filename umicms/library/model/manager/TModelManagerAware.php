<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model\manager;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для внедрения менеджера моделей данных.
 */
trait TModelManagerAware
{
    /**
     * @var ModelManager $traitModelManager менеджер объектов
     */
    private $traitModelManager;

    /**
     * @see IModelManagerAware::setModelManager()
     */
    public function setModelManager(ModelManager $modelManager)
    {
        $this->traitModelManager = $modelManager;
    }

    /**
     * Возвращает менеджер объектов
     * @throws RequiredDependencyException если менеджер объектов не установлен
     * @return ModelManager
     */
    protected function getModelManager()
    {
        if (!$this->traitModelManager) {
            throw new RequiredDependencyException(sprintf(
                'Model manager is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitModelManager;
    }
}
 