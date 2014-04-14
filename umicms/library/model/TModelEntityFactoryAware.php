<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для внедрения фабрики сущностей моделей данных.
 */
trait TModelEntityFactoryAware
{

    /**
     * @var ModelEntityFactory $traitEntityFactory фабрика сущностей моделей данных
     */
    private $traitEntityFactory;

    /**
     * @see IModelEntityFactoryAware::setModelEntityFactory()
     */
    public function setModelEntityFactory(ModelEntityFactory $entityFactory)
    {
        $this->traitEntityFactory = $entityFactory;
    }

    /**
     * Возвращает фабрику сущностей моделей данных.
     * @throws RequiredDependencyException если фабрика не была внедрена
     * @return ModelEntityFactory
     */
    protected function getModelEntityFactory()
    {
        if (!$this->traitEntityFactory) {
            throw new RequiredDependencyException(sprintf(
                'Model entity factory is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitEntityFactory;
    }
}
 