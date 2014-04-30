<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\acl\toolbox\AclTools;
use umi\authentication\toolbox\AuthenticationTools;
use umi\dbal\toolbox\DbalTools;
use umi\filter\toolbox\FilterTools;
use umi\form\toolbox\FormTools;
use umi\i18n\toolbox\I18nTools;
use umi\orm\toolbox\ORMTools;
use umi\pagination\toolbox\PaginationTools;
use umi\rss\toolbox\RssTools;
use umi\stemming\toolbox\StemmingTools;
use umi\stream\toolbox\StreamTools;
use umi\validation\toolbox\ValidationTools;
use umicms\model\toolbox\ModelTools;
use umicms\module\toolbox\ModuleTools;
use umicms\serialization\toolbox\SerializationTools;

return [
    I18nTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/i18n/toolbox/config.php'),
    DbalTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/dbal/toolbox/config.php'),
    ORMTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/orm/toolbox/config.php'),
    FormTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/form/toolbox/config.php'),
    FilterTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/filter/toolbox/config.php'),
    ValidationTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/validation/toolbox/config.php'),
    AuthenticationTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/authentication/toolbox/config.php'),
    StemmingTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/stemming/toolbox/config.php'),
    AclTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/acl/toolbox/config.php'),
    RssTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/rss/toolbox/config.php'),
    PaginationTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/pagination/toolbox/config.php'),
    StreamTools::NAME => require(FRAMEWORK_LIBRARY_DIR . '/stream/toolbox/config.php'),
    ModuleTools::NAME => require(CMS_LIBRARY_DIR . '/module/toolbox/config.php'),
    ModelTools::NAME => require(CMS_LIBRARY_DIR . '/model/toolbox/config.php'),
    SerializationTools::NAME => require(CMS_LIBRARY_DIR . '/serialization/toolbox/config.php')
];