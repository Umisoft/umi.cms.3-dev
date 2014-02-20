<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\object\CmsObject;

/**
 * Новостной сюжет.
 *
 * @property string $metaKeywords мета-тег keywords
 * @property string $metaDescription мета-тег description
 * @property string $metaTitle мета-тег title
 * @property string $h1 заголовок
 * @property string $content содержание
 * @property string $slug последней часть ЧПУ
 */
class NewsSubject extends CmsObject
{
    /**
     * Имя поля для хранения последней части ЧПУ сюжет
     */
    const FIELD_SLUG = 'slug';
}
 