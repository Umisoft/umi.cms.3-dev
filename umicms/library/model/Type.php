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

/**
 * Тип данных
 */
class Type
{
    /**
     * @var string $name имя типа
     */
    protected $name;
    /**
     * @var bool $isLocked флаг "заблокировано"
     */
    protected $isLocked = false;
    /**
     * @var bool $isDeleted флаг "удаленный"
     */
    protected $isDeleted = false;
    /**
     * @var bool $isModified флаг "измененный"
     */
    protected $isModified = false;
    /**
     * @var bool $isNew флаг "новый"
     */
    protected $isNew = false;
    /**
     * @var string $objectClassName полное квалифицированное имя класса
     */
    protected $objectClassName;
    /**
     * @var Group[] $groups список экземпляров групп типа, массив вида [$groupName => Group, ...]
     */
    protected $groups = [];
    /**
     * @var Field[] $fields список экземпляров полей типа, массив вида [$fieldName => Field, ...]
     */
    protected $fields = [];
    /**
     * @var array $groupFields список экземпляров полей в группах, массив вида [$groupName => [$fieldName => Field, ...]...]
     */
    protected $groupFields = [];

    /**
     * Возвращает уникальное имя типа
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Проверяет, помечен ли объект как "удаленный"
     * @return bool
     */
    public function getIsDeleted() {
        return $this->isDeleted;
    }

    /**
     * Устанавливает/снимает флаг "удаленный"
     * @param bool $isDeleted
     * @return $this
     */
    public function setIsDeleted($isDeleted = true) {
        $this->isDeleted = (bool) $isDeleted;

        return $this;
    }

    /**
     * Проверяет, помечен ли объект как "новый"
     * @return bool
     */
    public function getIsNew() {
        return $this->isNew;
    }

    /**
     * Устанавливает/снимает флаг "новый"
     * @param bool $isNew
     * @return $this
     */
    public function setIsNew($isNew = true) {
        $this->isNew = (bool) $isNew;

        return $this;
    }

    /**
     * Проверяет, помечен ли объект как "измененный"
     * @return bool
     */
    public function getIsModified() {
        return $this->isModified;
    }

    /**
     * Устанавливает/снимает флаг "измененный"
     * @param bool $isModified
     * @return $this
     */
    public function setIsModified($isModified = true) {
        $this->isModified = (bool) $isModified;

        return $this;
    }

    /**
     * Проверяет, заблокирован ли тип на удаление
     * @return bool
     */
    public function getIsLocked() {
        return $this->isLocked;
    }

    /**
     * Блокирует или разблокирует тип
     * @param bool $isLocked
     * @return $this
     */
    public function setIsLocked($isLocked = true) {
        $this->isLocked = (bool) $isLocked;

        return $this;
    }

    /**
     * Возвращает имя класса для создания экземпляров объектов данного типа.
     * @return string|null имя класса, либо null если используется класс по умолчанию
     */
    public function getObjectClass() {
        return $this->objectClassName;
    }

    /**
     * Установливает имя класса для создания экземпляров объектов данного типа
     * @param string $objectClassName полное квалифицированное имя класса
     * @return $this
     */
    public function setObjectClass($objectClassName) {
        if ($this->objectClassName != $objectClassName) {
            $this->objectClassName = $objectClassName;
            $this->setIsModified(true);
        }

        return $this;
    }

    /**
     * Заполняет тип группами.
     * @internal
     * @param Group[] $groups
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Заполняет тип полями
     * @internal
     * @param Field[] $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Заполняет тип полями, распределенными по группам
     * @internal
     * @param array $fields массив объектов Field по группам
     * @return $this
     */
    public function setGroupFields($fields)
    {
        $this->groupFields = $fields;

        return $this;
    }
}
 