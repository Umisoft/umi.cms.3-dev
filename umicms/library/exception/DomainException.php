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
 * Исключения, связанные тем, что значение не придерживается определенных правил текущего контекста.
 * Например, если не существует класс, который передан в опциях, либо класс не следует
 * необходимому контракту.
 */
class DomainException extends \DomainException implements IException
{
}
