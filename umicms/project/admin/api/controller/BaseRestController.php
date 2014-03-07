<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\hmvc\controller\BaseController;

/**
 * Базовый REST-контроллер
 */
abstract class BaseRestController extends BaseController
{
    /**
     * Возвращает данные, полученные в теле POST- или PUT-запроса, в виде массива.
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    protected function getIncomingData()
    {
        $inputData = file_get_contents('php://input');
        if (!$inputData) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request body is empty.');
        }

        $data = @json_decode($inputData, true);

        if ($error = json_last_error()) {
            if (function_exists('json_last_error_msg')) {
                $error = json_last_error_msg();
            }
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'JSON parse error: ' . $error);
        }

        return $data;
    }
}
 