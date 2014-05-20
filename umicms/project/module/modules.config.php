<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

return [
    'umicms\project\module\structure\api\StructureModule' => [],
    'umicms\project\module\blog\api\BlogModule' => [],
    'umicms\project\module\users\api\UsersModule' => [],


    'umicms\project\module\search\api\SearchModule' => '{#lazy:~/project/module/search/configuration/module.config.php}',
    'umicms\project\module\news\api\NewsModule' => '{#lazy:~/project/module/news/configuration/module.config.php}'
];