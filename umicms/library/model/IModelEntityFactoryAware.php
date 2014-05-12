<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model;

use umicms\model\toolbox\factory\ModelEntityFactory;

/**
 * Интерфейс для внедрения фабрики сущностей моделей данных.
 */
interface IModelEntityFactoryAware
{

    /**
     * Устанавливает фабрику сущностей моделей данных.
     * @param ModelEntityFactory $entityFactory
     */
    public function setModelEntityFactory(ModelEntityFactory $entityFactory);
}
 