<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
