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
use umicms\project\module\seo\model\YandexModel;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.seo.yandex'
        ]
    ],

    'elements' => [
        YandexModel::YANDEX_HOST_ID => [
            'type' => Text::TYPE_NAME,
            'label' => YandexModel::YANDEX_HOST_ID,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_INT => []
                ],
                'dataSource' => YandexModel::YANDEX_HOST_ID
            ]
        ],
        YandexModel::YANDEX_OAUTH_TOKEN => [
            'type' => Text::TYPE_NAME,
            'label' => YandexModel::YANDEX_OAUTH_TOKEN,
            'options' => [
                'dataSource' => YandexModel::YANDEX_OAUTH_TOKEN
            ]
        ]
    ]
];