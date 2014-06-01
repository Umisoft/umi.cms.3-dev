<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\field;

use Doctrine\DBAL\Schema\Table;
use umi\config\entity\IConfig;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;

/**
 * Поле модели данных.
 */
abstract class BaseField implements ILocalizable
{
    use TLocalizable;

    /**
     * @var string $name имя поля
     */
    protected $name;
    /**
     * @var IConfig $metadata конфигурация метаданных поля
     */
    protected $metadata;
    /**
     * @var Table $table схема таблицы
     */
    protected $table;

    /**
     * Конструктор.
     * @param string $name имя поля
     * @param IConfig $metadata конфигурация метаданных поля
     * @param Table $table схема таблицы
     */
    public function __construct($name, IConfig $metadata, Table $table)
    {
        $this->name = $name;
        $this->metadata = $metadata;
        $this->table = $table;
    }

    /**
     * Возвращает имя поля
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Проверяет, доступно ли поле на запись
     * @return boolean
     */
    public function getIsReadOnly()
    {
        return (bool) $this->metadata->get('readOnly') ?: false;
    }

    /**
     * Выставляет доступность поля на запись
     * @param bool $readOnly
     * @return $this
     */
    public function setIsReadOnly($readOnly = true)
    {
        $this->metadata->set('readOnly', (bool) $readOnly);

        return $this;
    }

    /**
     * Возвращает имя столбца таблицы для поля
     * @return string
     */
    public function getColumnName()
    {
        return $this->metadata->get('columnName') ?: $this->name;
    }

    /**
     * Возвращает имя getter'а для доступа к значению поля
     * @return string
     */
    public function getAccessor()
    {
        return $this->metadata->get('accessor');
    }

    /**
     * Выставляет имя getter'а для доступа к значению поля
     * @param string $accessor
     * @return $this
     */
    public function setAccessor($accessor)
    {
        $this->metadata->set('accessor', $accessor);

        return $this;
    }

    /**
     * Возвращает имя setter'а для установки значения поля
     * @return string
     */
    public function getMutator()
    {
        return $this->metadata->get('mutator');
    }

    /**
     * Выставляет имя setter'а для установки значения поля
     * @param string $mutator
     * @return $this
     */
    public function setMutator($mutator)
    {
        $this->metadata->set('mutator', $mutator);

        return $this;
    }

    /**
     * Возвращает значение поля по умолчанию (которое будет сохраняться в БД при создании объекта).
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->metadata->get('defaultValue');
    }

    /**
     * Выставляет значение поля по умолчанию (которое будет сохраняться в БД при создании объекта).
     * @param mixed $defaultValue
     * @return $this
     */
    public function setDefaultValue($defaultValue)
    {
        $this->metadata->set('defaultValue', $defaultValue);
        $this->table->getColumn($this->getColumnName())->setDefault($defaultValue);

        return $this;
    }

}