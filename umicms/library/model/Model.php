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

use Doctrine\DBAL\Schema\Table;
use umi\config\entity\IConfig;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\AlreadyExistentEntityException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\UnexpectedValueException;
use umicms\model\manager\IModelManagerAware;
use umicms\model\manager\TModelManagerAware;

/**
 * Модель данных
 */
class Model implements ILocalizable, IModelEntityFactoryAware, IModelManagerAware
{

    use TLocalizable;
    use TModelEntityFactoryAware;
    use TModelManagerAware;

    /**
     * @var string $name имя модели
     */
    protected $name;
    /**
     * @var IConfig $config конфигурация модели
     */
    protected $config;
    /**
     * @var array $typeNames список имен всех типов модели
     */
    protected $typeNames;
    /**
     * @var Type[] $types массив всех загруженных экземпляров типов
     */
    protected $types = [];
    /**
     * @var Table $tableScheme схема таблицы модели в БД
     */
    private $tableScheme;

    /**
     * Конструктор.
     * @param string $name имя модели
     * @param IConfig $config конфигурация
     */
    public function __construct($name, IConfig $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * Возвращает имя модели.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Помечает модель как измененную
     * @return $this
     */
    public function setIsModified()
    {
        $this->getModelManager()->markAsModified($this);
        return $this;
    }

    /**
     * Возвращает тип данных по имени.
     * @param string $typeName имя типа
     * @throws NonexistentEntityException если типа с заданным именем не существует
     * @return Type
     */
    public function getType($typeName)
    {
        //TODO
    }

    /**
     * Проевряет, существует ли тип данных по имени.
     * @param string $typeName имя типа
     * @return bool
     */
    public function hasType($typeName)
    {
        //TODO
    }

    /**
     * Возвращает имя таблицы для хранения модели.
     * @return string
     */
    public function getTableName()
    {
        //TODO
    }

    /**
     * Создает и возвращает экземпляр нового типа с указанным именем
     * @param string $typeName имя типа
     * @throws AlreadyExistentEntityException если тип с указанным именем уже существует
     * @throws NonexistentEntityException если тип базовый тип, либо родительский тип не существует
     * @return Type
     */
    public function addType($typeName)
    {
        /*if ($this->hasType($typeName)) {
            throw new AlreadyExistentEntityException($this->translate(
                'Object type "{name}" already exists in "{model}".',
                ['name' => $typeName, 'model' => $this->getName()]
            ));
        }

        $parentTypeName = $this->getParentTypeName($typeName);

        if ($parentTypeName && !$this->hasType($parentTypeName)) {
            throw new NonexistentEntityException($this->translate(
                'Cannot create type "{name}". Parent type does not exist in "{model}".',
                ['name' => $typeName, 'model' => $this->getName()]
            ));
        }

        $type = new Type();
        $this->types[$typeName] = $type;
        $this->typeNames[] = $typeName;
        $this->setIsModified();

        if ($parentTypeName) {
            $parentType = $this->getType($parentTypeName);

            foreach ($parentType->getGroups() as $group) {
                $type->attachGroup($group);
                foreach ($parentType->getGroupFields($group->getName()) as $field) {
                    $type->attachField($field, $group->getName());
                }
            }

            if ($objectClassName = $parentType->getObjectClass()) {
                $type->setObjectClass($objectClassName);
            }
        }

        return $type;*/
    }

    /**
     * Возвращает схему таблицы для хранения модели.
     * @throws UnexpectedValueException если конфигурация невалидная
     * @return Table
     */
    public function getTableScheme()
    {
        if (!$this->tableScheme) {

            $tableConfig = $this->config->get('tableScheme');

            if(!$tableConfig instanceof IConfig) {
                throw new UnexpectedValueException(
                    $this->translate(
                        'Cannot load table scheme for model "{name}". Option "tableScheme" should be an array.',
                        ['name' => $this->name]
                    )
                );
            }

            $this->tableScheme = $this->getModelEntityFactory()->getTableSchemeLoader()->load($tableConfig);
        }

        return $this->tableScheme;
    }


}
 