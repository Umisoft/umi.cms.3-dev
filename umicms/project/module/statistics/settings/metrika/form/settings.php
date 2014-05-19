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
use umicms\project\module\statistics\admin\metrika\model\MetrikaModel;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.statistics.metrika'
        ]
    ],

    'elements' => [
        MetrikaModel::OAUTH_TOKEN => [
            'type' => Text::TYPE_NAME,
            'label' => MetrikaModel::OAUTH_TOKEN,
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'dataSource' => MetrikaModel::OAUTH_TOKEN
            ]
        ]
    ]
];