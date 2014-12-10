<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\model\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;

/**
 * Редирект
 *
 * @property ICmsPage $sourcePattern исходный шаблон URL
 * @property ICmsPage $targetPattern шаблон URL, куда будет производиться редирект
 */
class Redirect extends CmsObject
{
    /**
     *  Имя поля для хранения исходного шаблона URL
     */
    const FIELD_SOURCE_PATTERN = 'sourcePattern';

    /**
     *  Имя поля для хранения шаблона URL, куда будет проиозводиться редирект
     */
    const FIELD_TARGET_PATTERN = 'targetPattern';

}
