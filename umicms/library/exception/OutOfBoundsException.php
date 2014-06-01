<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\exception;

/**
 * Создается исключение, если значение не является действительным ключем.
 * Это соответствует ошибкам, которые не могут быть обнаружены во время компиляции.
 */
class OutOfBoundsException extends \OutOfBoundsException implements IException
{
}