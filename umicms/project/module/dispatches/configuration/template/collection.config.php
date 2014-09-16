<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\dispatches\model\collection\TemplateCollection;

return [
    'type'         => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatches\model\collection\TemplateCollection',
    'handlers'     => [
        'admin' => 'dispatches.template'
    ],
    'forms'        => [
        'base' => [
            TemplateCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatches/configuration/template/form/base.edit.config.php}',
            TemplateCollection::FORM_CREATE => '{#lazy:~/project/module/dispatches/configuration/template/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.dispatchTemplate',
        'collection'
    ]
];