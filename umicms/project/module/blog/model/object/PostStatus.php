<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\object;

use umicms\orm\object\CmsObject;

/**
 * Статус поста.
 */
class PostStatus extends CmsObject
{
    /**
     * GUID для статуса "Черновик"
     */
    const GUID_DRAFT = 'f13d7eaa-54bc-4b5a-8a68-721221fa4191';
    /**
     * GUID для статуса "Опубликован"
     */
    const GUID_PUBLISHED = '26b45376-edca-4e0a-982c-e4d956d9212d';
    /**
     * GUID для статуса "Отклонен"
     */
    const GUID_REJECTED = '74d25bd8-c025-4438-bb42-86aa4f47ac1c';
    /**
     * GUID для статуса "Требует модерации"
     */
    const GUID_NEED_MODERATION = '25ad8b79-bceb-4330-92c5-f3a65667c3c3';

}
 