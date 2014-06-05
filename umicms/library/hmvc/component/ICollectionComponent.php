<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 