<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\object;

use umicms\project\module\structure\object\Layout;

/**
 * Интерфейс страниц сайта.
 *
 * @property string $metaTitle заголовок окна браузера
 * @property string $metaKeywords ключевые слова
 * @property string $metaDescription описание страницы
 * @property string $content содержимое страницы
 * @property string $h1 заголовок страницы
 * @property Layout|null $layout шаблон для вывода
 */
interface ICmsPage
{
    /**
     *  Имя поля для хранения заголовка окна браузера
     */
    const FIELD_META_TITLE = 'metaTitle';
    /**
     * Имя поля для хранения ключевых слов страницы
     */
    const FIELD_META_KEYWORDS = 'metaKeywords';
    /**
     * Имя поля для хранения описания страницы
     */
    const FIELD_META_DESCRIPTION = 'metaDescription';
    /**
     * Имя поля для хранения заголовка страницы
     */
    const FIELD_H1 = 'h1';
    /**
     * Имя поля для хранения содержимого страницы
     */
    const FIELD_CONTENT = 'content';
    /**
     *  Имя поля для хранения шаблона
     */
    const FIELD_LAYOUT = 'layout';
}
 