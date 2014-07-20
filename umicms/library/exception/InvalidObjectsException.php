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

use Exception;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\orm\object\ICmsObject;

/**
 * Исключения, связанные с наличием невалидных объектов.
 */
class InvalidObjectsException extends HttpException
{
    /**
     * @var ICmsObject[] $invalidObjects объекты, не прошедшие валидацию
     */
    protected $invalidObjects = [];

    /**
     * {@inheritdoc}
     * @param ICmsObject[] $invalidObjects
     */
    public function __construct($message = "", array $invalidObjects = [], $code = 0, Exception $previous = null)
    {
        parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $previous);

        $this->invalidObjects = $invalidObjects;
    }

    /**
     * Возвращает список невалидных объектов.
     * @return ICmsObject[]
     */
    public function getInvalidObjects()
    {
        return $this->invalidObjects;
    }
}
 