<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Password;
use umi\form\element\Text;
use umicms\project\module\seo\model\MegaindexModel;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.seo.megaindex'
        ]
    ],

    'elements' => [
        MegaindexModel::MEGAINDEX_LOGIN => [
            'type' => Text::TYPE_NAME,
            'label' => MegaindexModel::MEGAINDEX_LOGIN,
            'options' => [
                'dataSource' => MegaindexModel::MEGAINDEX_LOGIN
            ]
        ],
        MegaindexModel::MEGAINDEX_PASSWORD => [
            'type' => Password::TYPE_NAME,
            'label' => MegaindexModel::MEGAINDEX_PASSWORD,
            'options' => [
                'dataSource' => MegaindexModel::MEGAINDEX_PASSWORD
            ]
        ],
        MegaindexModel::MEGAINDEX_SITE_URL => [
            'type' => Text::TYPE_NAME,
            'label' => MegaindexModel::MEGAINDEX_SITE_URL,
            'options' => [
                'dataSource' => MegaindexModel::MEGAINDEX_SITE_URL
            ]
        ]
    ]
];