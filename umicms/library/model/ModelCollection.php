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

use umi\config\entity\IConfig;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\exception\AlreadyExistentEntityException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\UnexpectedValueException;
use umicms\model\manager\IModelManagerAware;
use umicms\model\manager\TModelManagerAware;

/**
 * API для управления моделями данных
 */
class ModelCollection implements ILocalizable, IModelEntityFactoryAware, IModelManagerAware
{
    use TLocalizable;
    use TModelEntityFactoryAware;
    use TModelManagerAware;
    use TConfigSupport;

    /**
     * @var Model[] $models список моделей
     */
    protected $models;
    /**
     * @var array $modelsConfig конфигурация моделей данных
     */
    private $modelsConfig;

    /**
     * Конструктор.
     * @param array $modelsConfig конфигурация моделей
     */
    public function __construct(array $modelsConfig)
    {
        $this->modelsConfig = $modelsConfig;
    }

    /**
     * Помечает все модели как модифицированные.
     * @return $this
     */
    public function migrateAll()
    {
        foreach ($this->getModels() as $model) {
            $model->setIsModified();
        }

        return $this;
    }

    /**
     * Возвращает список имен моделей данных.
     * @return array
     */
    public function getModelNames()
    {
        return array_keys($this->modelsConfig);
    }

    /**
     * Проевряет, существует ли модель данных по имени.
     * @param string $modelName имя модели
     * @return bool
     */
    public function hasModel($modelName)
    {
        return isset($this->modelsConfig[$modelName]);
    }

    /**
     * Возвращает модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return Model
     */
    public function getModel($modelName)
    {
        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }

        $model = $this->getModelEntityFactory()->createModel($modelName, $this->getModelConfig($modelName));

        return $this->models[$modelName] = $model;

    }

    /**
     * Возвращает список всех моделей коллекции.
     * @return Model[]
     */
    public function getModels()
    {
        $result = [];
        foreach ($this->getModelNames() as $modelName) {
            $result[] = $this->getModel($modelName);
        }
        return $result;
    }

    /**
     * Добавляет модель.
     * @param string $modelName имя модели
     * @param IConfig $modelConfig конфигурация
     * @throws AlreadyExistentEntityException если модель с заданным именем существует
     * @return Model
     */
    public function addModel($modelName, IConfig $modelConfig)
    {
        if ($this->hasModel($modelName)) {
            throw new AlreadyExistentEntityException(
                $this->translate(
                    'Model "{modelName}" already exists.',
                    ['modelName' => $modelName]
                )
            );
        }

        $model = $this->getModelEntityFactory()->createModel($modelName, $modelConfig);
        $this->getModelManager()->markAsNew($model);

        return $this->models[$modelName] = $model;
    }

    /**
     * Удаляет модель данных по имени.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @return $this
     */
    public function deleteModel($modelName)
    {
        $model = $this->getModel($modelName);
        $this->getModelManager()->markAsDeleted($model);

        return $this;
    }

    /**
     * Возвращает конфигурацию модели данных.
     * @param string $modelName имя модели
     * @throws NonexistentEntityException если модели с заданным именем не существует
     * @throws UnexpectedValueException если конфигурация невалидная
     * @return IConfig
     */
    protected function getModelConfig($modelName)
    {
        if (!$this->hasModel($modelName)) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Model "{modelName}" does not exist.',
                    ['modelName' => $modelName]
                )
            );
        }

        $modelConfig = $this->modelsConfig[$modelName];
        if (!$modelConfig instanceof IConfig) {
            throw new UnexpectedValueException($this->translate(
                'Invalid model "{modelName}" configuration.',
                ['modelName' => $modelName]
            ));
        }

        return $modelConfig;
    }

}
 