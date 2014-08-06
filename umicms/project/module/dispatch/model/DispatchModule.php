<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\model;

use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatch\model\collection\DispatchCollection;
use umicms\project\module\dispatch\model\collection\ReasonCollection;
use umicms\project\module\dispatch\model\collection\ReleaseCollection;
use umicms\project\module\dispatch\model\collection\SubscriberCollection;
use umicms\project\module\dispatch\model\collection\TemplateMailCollection;
use umicms\project\module\dispatch\model\object\Dispatches;
use umicms\project\module\dispatch\model\object\Reason;
use umicms\project\module\dispatch\model\object\Release;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\TemplateMail;

/**
 * Модуль "Рассылки".
 */
class DispatchModule extends BaseModule
{

}
