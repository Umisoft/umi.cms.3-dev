<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\toolbox;

/**
 * Конфигурация для регистрации набора инструментов.
 */
return [
    'name'     => ModuleTools::NAME,
    'class'    => __NAMESPACE__ . '\ApiTools',
    'awareInterfaces' => [
        'umicms\module\IModuleAware',
        'umicms\module\model\IModelEntityFactoryAware',
    ],
    'services' => [
        'umicms\module\BaseModule'
    ]
];
