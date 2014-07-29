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
 * Статус комментария.
 */
class CommentStatus extends CmsObject
{
    /**
     * GUID для статуса "Опубликован"
     */
    const GUID_PUBLISHED = '36b939b6-8144-4869-8b4e-2d9302c48f09';
    /**
     * GUID для статуса "Отклонен"
     */
    const GUID_REJECTED = 'dd1f6a73-84cd-4d45-a5ff-17b6bf9bef31';
    /**
     * GUID для статуса "Снят с публикации"
     */
    const GUID_UNPUBLISHED = '31d5db27-3e6a-44c8-9080-bd88d5111f9a';
    /**
     * GUID для статуса "Требует модерации"
     */
    const GUID_NEED_MODERATION = '2176ce30-f996-4d63-a5fd-4fb75a22b82e';

}
 