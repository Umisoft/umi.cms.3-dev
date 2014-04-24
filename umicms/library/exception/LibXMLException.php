<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\exception;

use LibXMLError;

/**
 * Исключения, связанные с работой LibXML
 */
class LibXMLException extends \RuntimeException implements IException
{
    public function __construct(libXMLError $error) {
        parent::__construct($error->message, $error->code);
    }
}
 