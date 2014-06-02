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
 * Исключения, связанные с попыткой выполнить операцию, которая запрещена по каким-либо причинам.
 * Например, при попытки удалить базовый тип, либо при попытке удалить поле, которое есть у родительского типа.
 */
class NotAllowedOperationException extends RuntimeException
{
}
