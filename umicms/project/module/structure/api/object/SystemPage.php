<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\api\object;

/**
 * Системная страница UMI.CMS.
 *
 * @property bool $skipInBreadcrumbs пропускать ли системную страницу при выводе хлебных крошек
 */
class SystemPage extends StructureElement
{
    /**
     * Тип объекта
     */
    const TYPE = 'system';
    /**
     * Имя поля для обозначения игнорирования вывода системной страницы в хлебных крошках
     */
    const FIELD_SKIP_PAGE_IN_BREADCRUMBS = 'skipInBreadcrumbs';
}
