<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model;

use umicms\exception\RequiredDependencyException;
use umicms\model\toolbox\factory\ModelEntityFactory;

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
 