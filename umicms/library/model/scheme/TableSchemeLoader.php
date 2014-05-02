<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model\scheme;

use Doctrine\DBAL\Schema\Table;
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
     * Загружает таблицу из конфигурации.
     * @param IConfig $config конфигурация
     * @return Table
     */
    public function load(IConfig $config)
    {
        $table = $this->createTable($config);
        $this->loadColumns($table, $config);

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
        if(!$name = $config->get('name')) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "name" is required.'
                )
            );
        }

        $options = $config->get('options') ?: [];
        $options = $this->configToArray($options, true);

        return new Table($name, [], [], [], 0, $options);

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

        if(!$columnsConfig instanceof IConfig) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot load table scheme from configuration. Option "columns" should be an array.'
                )
            );
        }

        foreach ($columnsConfig as $columnName => $columnConfig) {

            if(!$columnConfig instanceof IConfig) {
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
}
 