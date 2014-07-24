<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model;

use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\collection\DispatchesCollection;
use umicms\project\module\dispatches\model\collection\ReasonCollection;
use umicms\project\module\dispatches\model\collection\ReleaseCollection;
use umicms\project\module\dispatches\model\collection\SubscribersCollection;
use umicms\project\module\dispatches\model\collection\TemplateMailCollection;
use umicms\project\module\dispatches\model\object\Dispatches;
use umicms\project\module\dispatches\model\object\Reason;
use umicms\project\module\dispatches\model\object\Release;
use umicms\project\module\dispatches\model\object\Subscribers;
use umicms\project\module\dispatches\model\object\TemplateMail;

/**
 * Модуль "Рассылки".
 */
class DispatchesModule extends BaseModule
{

}
