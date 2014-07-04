<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\view;

use umi\hmvc\view\View;
use umicms\serialization\ISerializerConfigurator;
use umicms\serialization\TSerializerConfigurator;

/**
 * Содержимое результата работы виджета или контроллера, требующее шаблонизации.
 */
class CmsView extends View implements ISerializerConfigurator
{
    use TSerializerConfigurator;
}
 