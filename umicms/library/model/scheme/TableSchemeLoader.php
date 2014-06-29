<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\scheme;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use umi\config\entity\IConfig;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\spl\config\TConfigSupport;
use umicms\exception\UnexpectedValueException;

/**
 * Класс для загрузки схемы таблицы из конфигурации
 */
class TableSchemeLoader implements ILocalizable
{
    use TLocalizable;
    use TConfigSupport;

    /**
     * @var string $tableNamePrefix префикс для имен таблиц проекта
     */
    public $tableNamePrefix = '';

    /**
     * Загружает таблицу из конфигурации.
     * @param IConfig $config конфигурация
     * @return Table
     */
    public function load(IConfig $config)
    {
        $table = $this->createTable($config);
        $this->loadColumns($table, $config);
        $this->loadIndexes($table, $config);
        $this->loadConstraints($table, $config);

        return $table;
    }

    /**
     * Создает пустую таблицу из конфигурации
     * @param IConfig $config конфигурация
     * @throws UnexpectedValueException если конфигурация невалидная
     * @return Table
     */
    protected function createTable(IConfig $config)
    {
        if (!$name = $config->get('name')) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "name" is required.'
                )
            );
        }


        $options = $config->get('options') ?: [];
        $options = $this->configToArray($options, true);

        return $this->createTableScheme($name, $options);

    }

    /**
     * Добаляет внешние ключи в таблицу.
     * @param Table $table
     * @param IConfig $config
     * @throws UnexpectedValueException
     */
    protected function loadConstraints(Table $table, IConfig $config)
    {
        $constraintsConfig = $config->get('constraints');

        if (!$constraintsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "constraints" should be an array.'
                )
            );
        }

        foreach ($constraintsConfig as $constraintName => $constraintConfig) {
            if (!$constraintConfig instanceof IConfig) {
                throw new UnexpectedValueException(
                    $this->translate(
                        'Cannot load table scheme from configuration. Constraint "{name}" configuration should be an array.',
                        ['name' => $constraintName]
                    )
                );
            }

            $this->loadConstraint($table, $constraintName, $constraintConfig);
        }
    }

    /**
     * Добавляет внешний ключ в таблицу.
     * @param Table $table
     * @param string $constraintName
     * @param IConfig $constraintConfig
     * @throws UnexpectedValueException
     */
    protected function loadConstraint(Table $table, $constraintName, IConfig $constraintConfig)
    {
        if (!$foreignTableName = $constraintConfig->get('foreignTable')) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load constraint configuration. Option "foreignTable" required.'
                )
            );
        }

        if ($foreignTableName === '%self%') {
            $foreignTableName = $table->getName();
        }

        $columnsConfig = $constraintConfig->get('columns');

        if (!$columnsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load constraint configuration. Option "columns" required and should be an array.'
                )
            );
        }

        $foreignColumnsConfig = $constraintConfig->get('foreignColumns');
        if (!$foreignColumnsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load constraint configuration. Option "foreignColumns" required and should be an array.'
                )
            );
        }

        $foreignTable = $this->createTableScheme($foreignTableName);
        foreach ($foreignColumnsConfig as $columnName => $config)
        {
            $foreignTable->addColumn($columnName, Type::BIGINT);
        }


        $table->addForeignKeyConstraint(
            $foreignTable,
            array_keys($columnsConfig->toArray()),
            array_keys($foreignColumnsConfig->toArray()),
            $constraintConfig->has('options') ? $constraintConfig->get('options')->toArray() : [],
            'fk_' . $table->getName() . '_' . $constraintName
        );
    }

    /**
     * Добаляет индексы в таблицу.
     * @param Table $table
     * @param IConfig $config
     * @throws UnexpectedValueException
     */
    protected function loadIndexes(Table $table, IConfig $config)
    {
        $indexesConfig = $config->get('indexes');

        if (!$indexesConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "indexes"  required and should be an array.'
                )
            );
        }

        foreach ($indexesConfig as $indexName => $indexConfig) {
            if (!$indexConfig instanceof IConfig) {
                throw new UnexpectedValueException(
                    $this->translate(
                        'Cannot load table scheme from configuration. Index "{name}" configuration should be an array.',
                        ['name' => $indexName]
                    )
                );
            }

            $this->loadIndex($table, $indexName, $indexConfig);
        }
    }

    /**
     * Добавляет индекс в таблицу.
     * @param Table $table
     * @param string $indexName
     * @param IConfig $indexConfig
     * @throws UnexpectedValueException
     */
    protected function loadIndex(Table $table, $indexName, IConfig $indexConfig)
    {
        $columnsConfig = $indexConfig->get('columns');
        if (!$columnsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load index "{indexName}" info. Option "columns" should be an array.',
                    ['indexName' => $indexName]
                )
            );
        }
        $columnNames = array_keys($columnsConfig->toArray());

        if ($indexConfig->get('type') == 'primary') {
            $table->setPrimaryKey($columnNames);
        } elseif ($indexConfig->get('type') == 'unique') {
            $table->addUniqueIndex($columnNames, 'uidx_' . $indexName);
        } else {
            $flags = [];
            if ($indexConfig->get('flags') instanceof IConfig) {
                $flags = $indexConfig->get('flags')->toArray();
            }

            $table->addIndex($columnNames, 'idx_' . $indexName, $flags);
        }
    }

    /**
     * Добаляет колонки в таблицу.
     * @param Table $table
     * @param IConfig $config
     * @throws UnexpectedValueException
     */
    protected function loadColumns(Table $table, IConfig $config)
    {
        $columnsConfig = $config->get('columns');

        if (!$columnsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "columns" should be an array.'
                )
            );
        }

        foreach ($columnsConfig as $columnName => $columnConfig) {

            if (!$columnConfig instanceof IConfig) {
                throw new UnexpectedValueException(
                    $this->translate(
                        'Cannot load table scheme from configuration. Column "{name}" configuration should be an array.',
                        ['name' => $columnName]
                    )
                );
            }

            $this->loadColumn($table, $columnName, $columnConfig);

        }
    }

    /**
     * Добавляет колонку в таблицу.
     * @param Table $table
     * @param string $columnName
     * @param IConfig $columnConfig
     * @throws UnexpectedValueException
     */
    protected function loadColumn(Table $table, $columnName, IConfig $columnConfig)
    {
        if (!$type = $columnConfig->get('type')) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "type" is required for column "{name}".',
                    ['name' => $columnName]
                )
            );
        }

        $options = $columnConfig->get('options') ? : [];
        $options = $this->configToArray($options, true);

        $table->addColumn($columnName, $type, $options);
    }

    /**
     * Создает схему таблицы
     * @param string $name имя таблицы
     * @param array $options опции
     * @return Table
     */
    private function createTableScheme($name, array $options = [])
    {
        return new Table($this->tableNamePrefix . $name, [], [], [], 0, $options);
    }
}
 