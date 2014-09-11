<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umi\orm\metadata\IObjectType;
use umicms\project\module\dispatch\model\collection\ReasonCollection;
use umicms\project\module\dispatch\model\object\Reason;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\dispatch\model\collection\ReasonCollection',
    'handlers' => [
        'admin' => 'dispatch.reason',
        //'site' => 'dispatch.dispatch'
    ],
    'forms' => [
        'base' => [
            ReasonCollection::FORM_EDIT => '{#lazy:~/project/module/dispatch/configuration/reason/form/base.edit.config.php}',
            ReasonCollection::FORM_CREATE => '{#lazy:~/project/module/dispatch/configuration/reason/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.reason', 'collection'
    ]
];