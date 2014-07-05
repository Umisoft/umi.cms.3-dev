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
use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\UnexpectedValueException;

/**
 * Модель данных.
 * Предназначена для конфгурирования ресурсов коллекции.
 */
class Model implements ILocalizable, IConfigIOAware, IModelEntityFactoryAware
{
    use TLocalizable;
    use TConfigIOAware;
    use TModelEntityFactoryAware;

    /**
     * @var string $name имя модели
     */
    protected $name;
    /**
     * @var string $name путь к файлу с конфигурацией схемы таблицы коллекции
     */
    protected $schemeConfigPath;
    /**
     * @var string $metadataConfigPath путь к файлу с конфигурацией метаданных коллекции
     */
    protected $metadataConfigPath;
    /**
     * @var string $collectionConfigPath путь к файлу с конфигурацией коллекции
     */
    protected $collectionConfigPath;

    /**
     * @var Table $tableScheme схема таблицы модели в БД
     */
    private $tableScheme;

    /**
     * Конструктор.
     * @param string $name имя модели
     * @param string $schemeConfigPath символический путь к файлу с конфигурацией схемы таблицы
     * @param string $metadataConfigPath символический путь к файлу с конфигурацией метаданных коллекции
     * @param string $collectionConfigPath символический путь к файлу с конфигурацией коллекции
     */
    public function __construct($name, $schemeConfigPath, $metadataConfigPath, $collectionConfigPath)
    {
        $this->name = $name;
        $this->schemeConfigPath = $schemeConfigPath;
        $this->metadataConfigPath = $metadataConfigPath;
        $this->collectionConfigPath = $collectionConfigPath;
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
     * Возвращает схему таблицы для хранения модели.
     * @throws UnexpectedValueException если конфигурация невалидная
     * @return Table
     */
    public function getTableScheme()
    {
        if (!$this->tableScheme) {
            $tableConfig = $this->readConfig($this->schemeConfigPath);
            $this->tableScheme = $this->getModelEntityFactory()->getTableSchemeLoader()->load($tableConfig);
        }

        return $this->tableScheme;
    }


}
 