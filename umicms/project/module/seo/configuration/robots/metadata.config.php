<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\seo\model\object\Robots;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'robots'
        ],
        'fields' => [
            Robots::FIELD_PAGE_RELATION => [
                'type' => IField::TYPE_OBJECT_RELATION,
                'columnName' => 'page_relation'
            ],
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\seo\model\object\Robots',
                'fields' => [
                    Robots::FIELD_PAGE_RELATION => []
                ]
            ]
        ]
    ]
);
