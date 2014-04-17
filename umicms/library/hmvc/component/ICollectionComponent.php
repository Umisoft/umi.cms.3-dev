<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\component;

use umi\hmvc\component\IComponent;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;

/**
 * Интерфейс компонента, работающего с коллекцией объектов
 */
interface ICollectionComponent extends IComponent
{
    /**
     * Опция для задания имени коллекции, с которой работает компонент.
     */
    const OPTION_COLLECTION_NAME = 'collectionName';

    /**
     * Возвращает коллекцию, с которой работает компонент.
     * @throws RuntimeException если в конфигурации не указано имя коллекции
     * @return ICmsCollection
     */
    public function getCollection();
}
 