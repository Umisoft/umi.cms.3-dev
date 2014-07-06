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

use LibXMLError;

/**
 * Исключения, связанные с работой LibXML
 */
class LibXMLException extends \RuntimeException implements IException
{
    /**
     * @var LibXMLError[] $errors
     */
    protected $errors;

    /**
     * {@inheritdoc}
     * @param string $message
     * @param int $code
     * @param LibXMLError[] $errors
     */
    public function __construct($message = "", $code = 0, array $errors) {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    /**
     * Возвращает ошибки, породившие исключение
     * @return LibXMLError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
 