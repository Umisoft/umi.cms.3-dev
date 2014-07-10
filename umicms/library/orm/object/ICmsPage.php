<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\project\module\structure\model\object\Layout;

/**
 * Интерфейс страниц сайта.
 *
 * @property string $metaTitle заголовок окна браузера
 * @property string $metaKeywords ключевые слова
 * @property string $metaDescription описание страницы
 * @property string $contents содержимое страницы
 * @property string $h1 заголовок страницы
 * @property string $slug последней часть ЧПУ
 * @property Layout|null $layout шаблон для вывода
 */
interface ICmsPage extends ICmsObject, IRecoverableObject, IRecyclableObject, IActiveAccessibleObject
{
    /**
     *  Имя поля для хранения заголовка окна браузера
     */
    const FIELD_PAGE_META_TITLE = 'metaTitle';
    /**
     * Имя поля для хранения ключевых слов страницы
     */
    const FIELD_PAGE_META_KEYWORDS = 'metaKeywords';
    /**
     * Имя поля для хранения описания страницы
     */
    const FIELD_PAGE_META_DESCRIPTION = 'metaDescription';
    /**
     * Имя поля для хранения заголовка страницы
     */
    const FIELD_PAGE_H1 = 'h1';
    /**
     * Имя поля для хранения содержимого страницы
     */
    const FIELD_PAGE_CONTENTS = 'contents';
    /**
     *  Имя поля для хранения шаблона
     */
    const FIELD_PAGE_LAYOUT = 'layout';
    /**
     * Имя поля для хранения последней части ЧПУ
     */
    const FIELD_PAGE_SLUG = 'slug';

    /**
     * Возвращает URL страницы для отображения на сайте.
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getPageUrl($isAbsolute = false);

    /**
     * Возвращает значение для H1, взятое из соответсвующего свойства, или из displayName, если H1 не заполнен.
     * @return string
     */
    public function getHeader();

    /**
     * Возвращает признак существования страницы в индексе.
     * @return bool
     */
    public function isInIndex();

}
 