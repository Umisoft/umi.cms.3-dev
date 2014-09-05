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
use umicms\project\module\dispatch\model\collection\ReleaseCollection;
use umicms\project\module\dispatch\model\object\Release;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\dispatch\model\collection\ReleaseCollection',
    'handlers' => [
        'admin' => 'dispatch.release',
        'site' => 'dispatch.release'
    ],
    'forms' => [
        'base' => [
            ReleaseCollection::FORM_EDIT => '{#lazy:~/project/module/dispatch/configuration/release/form/base.edit.config.php}',
            ReleaseCollection::FORM_CREATE => '{#lazy:~/project/module/dispatch/configuration/release/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.release', 'collection'
    ]
];