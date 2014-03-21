<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\seo\model;

use umi\hmvc\model\IModel;

/**
 * Class MegaindexModel
 */
class MegaindexModel implements IModel
{
    public $password;

    public function queryApi($method, $params)
    {
        return json_decode(file_get_contents(
            'http://api.megaindex.ru/?' . http_build_query(
                [
                    'method' => $method,
                    'login' => 'megaindex-api-test@megaindex.ru',
                    'password' => 123456,
                    'lr' => 2,
                    'url' => 'megaindex.ru',
                    'date' => '2012-07-20'
                ]
            )
        ), true);
    }
}
