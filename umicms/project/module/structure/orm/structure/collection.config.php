<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'handlers' => [
        'admin' => 'structure.page',
        'site' => 'structure'
    ],
    'forms' => [
        'base' => [
            'edit' => '{#lazy:~/project/module/structure/orm/structure/form/base.edit.config.php}'
        ]
    ]
];