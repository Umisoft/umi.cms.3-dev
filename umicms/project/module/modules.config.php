<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'umicms\project\module\structure\model\StructureModule' => [],
    'umicms\project\module\blog\model\BlogModule' => [],

    'umicms\project\module\users\model\UsersModule' => '{#lazy:~/project/module/users/configuration/module.config.php}',
    'umicms\project\module\search\model\SearchModule' => '{#lazy:~/project/module/search/configuration/module.config.php}',
    'umicms\project\module\news\model\NewsModule' => '{#lazy:~/project/module/news/configuration/module.config.php}'
];