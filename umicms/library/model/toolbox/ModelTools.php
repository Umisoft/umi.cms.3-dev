<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\model\IModelEntityFactoryAware;
use umicms\model\manager\ModelManager;
use umicms\model\ModelEntityFactory;

/**
 * Инструментарий для работы с моделями.
 */
class ModelTools implements IToolbox
{
    /**
     * Имя набора инструментов
     */
    const NAME = 'model';

    use TToolbox;

    /**
     * @var string $modelEntityFactoryClass класс для создания фабрики сущностей моделей данных
     */
    public $modelEntityFactoryClass = 'umicms\model\ModelEntityFactory';
    /**
     * @var string $modelManagerClass класс для создания менеджера моделей данных
     */
    public $modelManagerClass = 'umicms\model\manager\ModelManager';

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->registerFactory(
            'modelEntity',
            $this->modelEntityFactoryClass,
            ['umicms\model\ModelEntityFactory']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof IModelEntityFactoryAware) {
            $object->setModelEntityFactory($this->getModelEntityFactory());
        }
    }

    /**
     * Возвращает фабрику сущностей моделей данных.
     * @return ModelEntityFactory
     */
    protected function getModelEntityFactory()
    {
        return $this->getFactory('modelEntity');
    }

    /**
     * Возвращает менеджер моделей данных.
     * @return ModelManager
     */
    protected function getModelManager()
    {
        return $this->getPrototype(
            $this->modelManagerClass,
            ['umicms\model\manager\ModelManager']
        )
            ->createSingleInstance();
    }

}
