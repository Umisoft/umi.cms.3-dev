<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\structure\model;

use DateTime;
use umi\orm\metadata\IObjectType;
use umi\orm\object\HierarchicObject;

/**
 * Базовый элемент структуры.
 *
 * @property string $guid глобальный уникальный идентификатор (GUID)
 * @property IObjectType $type тип
 * @property string $displayName выводимое в интерфейсах имя
 * @property int $version версия
 * @property string $uri URI страницы
 * @property string $slug последняя часть ЧПУ
 * @property int $level уровень вложенности в иерархии
 * @property int $order порядок следования в иерархии
 * @property StructureElement|null $parent родительский элемент
 * @property int $childCount количество дочерних элементов
 * @property bool $active признак активности
 * @property bool $locked признак заблокированности элемента на удаление
 * @property DateTime $created время создания элемента
 * @property DateTime $updated время обновления элемента
 * @property string $module имя модуля-обработчика
 */
abstract class StructureElement extends HierarchicObject
{

}
