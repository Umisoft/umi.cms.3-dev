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
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\seo\model\collection\RedirectsCollection;
use umicms\project\module\seo\model\object\Redirect;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\seo\model\collection\RedirectsCollection',
    'handlers' => [
        'admin' => 'seo.redirects'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/seo/configuration/redirects/form/base.create.config.php}',
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/seo/configuration/redirects/form/base.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.redirects' => 'collection.redirects', 'collection' => 'collection'
    ],

    RedirectsCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        Redirect::FIELD_SOURCE_PATTERN => [],
        Redirect::FIELD_TARGET_PATTERN => [],
    ],
];