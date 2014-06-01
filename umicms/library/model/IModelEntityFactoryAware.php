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
 