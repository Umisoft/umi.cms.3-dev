<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model;

use Doctrine\DBAL\Schema\Table;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\AlreadyExistentEntityException;
use umicms\exception\NonexistentEntityException;

/**
 * Модель данных
 */
class Model implements ILocalizable
{

    use TLocalizable;

    /**
     * @var string $name имя модели
     */
    protected $name;
    /**
     * @var array $config конфигурация модели
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

    public function __construct($name, array $config)
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
        //TODO
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
        if ($this->hasType($typeName)) {
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

        return $type;
    }

    /**
     * Возвращает схему таблицы для хранения модели.
     * @return Table
     */
    public function getTableScheme()
    {
        //TODO
    }

}
 