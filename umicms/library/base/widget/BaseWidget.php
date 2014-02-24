<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\base\widget;

use umi\hmvc\widget\BaseWidget as FrameworkWidget;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;

/**
 * Базовый виджет UMI.CMS
 */
abstract class BaseWidget extends FrameworkWidget implements ILocalizable
{
    use TLocalizable;
}
 