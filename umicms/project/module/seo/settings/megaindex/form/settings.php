<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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