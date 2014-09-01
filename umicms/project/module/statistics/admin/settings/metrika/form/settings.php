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
use umicms\project\module\statistics\admin\metrika\model\MetrikaModel;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.statistics.metrika' => 'project.admin.rest.settings.statistics.metrika'
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