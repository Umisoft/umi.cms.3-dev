<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\dispatch\model\collection\TemplateMailCollection;

return [
    'type'         => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatch\model\collection\TemplateMailCollection',
    'handlers'     => [
        'admin' => 'dispatch.templatemail',
    ],
    'forms'        => [
        'base' => [
            TemplateMailCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatch/configuration/templatemail/form/base.edit.config.php}',
            TemplateMailCollection::FORM_CREATE => '{#lazy:~/project/module/dispatch/configuration/templatemail/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.templatemail',
        'collection'
    ]
];