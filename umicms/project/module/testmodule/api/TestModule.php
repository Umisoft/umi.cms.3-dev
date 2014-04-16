<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\testmodule\api;

use umicms\module\BaseModule;
use umicms\project\module\testmodule\api\collection\TestCollection;

/**
 * Репозиторий тестовый.
 */
class TestModule extends BaseModule
{
    /**
     * Возвращает коллекцию новостей.
     * @return TestCollection
     */
    public function test()
    {
        return $this->getCollection('testTest');
    }
}
 