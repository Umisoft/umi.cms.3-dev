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
use umicms\project\module\seo\model\YandexModel;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.seo.yandex' => 'project.admin.rest.settings.seo.yandex'
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