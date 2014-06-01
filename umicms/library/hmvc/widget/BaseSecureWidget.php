<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\acl\IAclResource;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\hmvc\view\CmsView;

/**
 * Базовый класс виджета UMI.CMS, доступ к которому контролируется через ACL.
 */
abstract class BaseSecureWidget extends BaseWidget implements IAclResource
{
    const ACL_RESOURCE_PREFIX = 'widget:';

    /**
     * @var string $forbiddenTemplate имя шаблона, по которому выводится виджет в случае отсутствия доступа к нему.
     */
    public $forbiddenTemplate = 'widget.forbidden';

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }

    /**
     * Формирует результат в случае отсутствия доступа к виджету.
     * @param ResourceAccessForbiddenException $e
     * @return CmsView
     */
    public function invokeForbidden(ResourceAccessForbiddenException $e)
    {
        return $this->createResult($this->forbiddenTemplate, ['error' => $e]);
    }
}
 