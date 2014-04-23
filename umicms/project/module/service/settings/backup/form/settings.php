<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
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