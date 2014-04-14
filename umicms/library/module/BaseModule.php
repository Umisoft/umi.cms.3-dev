<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\module\model\IModelEntityFactoryAware;
use umicms\module\model\ModelCollection;
use umicms\module\model\TModelEntityFactoryAware;

/**
 * Модуль
 */
abstract class BaseModule implements ICollectionManagerAware, IModelEntityFactoryAware
{
    use TCollectionManagerAware;
    use TModelEntityFactoryAware;

    /**
     * @var array $models конфигурация моделей, обслуживаемых модулем
     */
    public $models = [];

    /**
     * @var ModelCollection $modelCollection
     */
    protected $modelCollection;

    /**
     * Возвращает коллекцию моделей модуля.
     * @return ModelCollection
     */
    public function getModelCollection()
    {
        if (is_null($this->modelCollection)) {
            $this->modelCollection = $this->getModelEntityFactory()->createModelCollection($this->models);
        }

        return $this->modelCollection;
    }
}
 