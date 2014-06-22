<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site;

use umicms\templating\engine\smarty\SmartyTemplateEngine;

return [
    SiteApplication::OPTION_VIEW => [
        SmartyTemplateEngine::OPTION_COMPILE_DIR => '/home/georgy/htdocs/umi/www/lite-smarty/smarty/templates_c',
        SmartyTemplateEngine::OPTION_CACHE_DIR => '/home/georgy/htdocs/umi/www/lite-smarty/smarty/cache',
        SmartyTemplateEngine::OPTION_CONFIG_DIR => '/home/georgy/htdocs/umi/www/lite-smarty/smarty/configs'
    ]
];
