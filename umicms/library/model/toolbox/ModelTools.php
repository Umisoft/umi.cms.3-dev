<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\toolbox;

use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\model\IModelEntityFactoryAware;
use umicms\model\manager\IModelManagerAware;
use umicms\model\manager\ModelManager;
use umicms\model\toolbox\factory\ModelEntityFactory;

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
    public $modelEntityFactoryClass = 'umicms\model\toolbox\factory\ModelEntityFactory';
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
            ['umicms\model\toolbox\factory\ModelEntityFactory']
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
        if ($object instanceof IModelManagerAware) {
            $object->setModelManager($this->getModelManager());
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
