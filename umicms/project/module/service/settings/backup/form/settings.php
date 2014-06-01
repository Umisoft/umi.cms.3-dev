<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\form\element\Text;
use umicms\project\module\service\api\collection\BackupCollection;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.service.backup'
        ]
    ],

    'elements' => [
        BackupCollection::SETTING_OBJECT_HISTORY_SIZE => [
            'type' => Text::TYPE_NAME,
            'label' => BackupCollection::SETTING_OBJECT_HISTORY_SIZE,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_INT => []
                ],
                'dataSource' => BackupCollection::SETTING_OBJECT_HISTORY_SIZE
            ]
        ]
    ]
];