<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\ControllerPage;
use project\module\structure\model\object\WidgetPage;
use umi\orm\metadata\field\IField;
use umicms\project\module\structure\model\object\StaticPage;

return
    [
        'fields'     => [
            WidgetPage::FIELD_PARAMETERS => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'parameters',
            ],
            WidgetPage::FIELD_DESCRIPTION => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'description'
            ],
            'secondContents' => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'second_contents'
            ],
            ControllerPage::FIELD_RETURN_VALUE => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'return_value'
            ],
            ControllerPage::FIELD_TEMPLATE_NAME => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'template_name'
            ]
        ],
        'types'      => [
            StaticPage::TYPE => [
                'fields'      => [
                    'secondContents' => []
                ]
            ],
            ControllerPage::TYPE => [
                'objectClass' => 'project\module\structure\model\object\ControllerPage',
                'fields'      => [
                    ControllerPage::FIELD_DESCRIPTION => [],
                    ControllerPage::FIELD_RETURN_VALUE => [],
                    ControllerPage::FIELD_TEMPLATE_NAME => []
                ]
            ],
            WidgetPage::TYPE           => [
                'objectClass' => 'project\module\structure\model\object\WidgetPage',
                'fields'      => [
                    WidgetPage::FIELD_PARAMETERS => [],
                    WidgetPage::FIELD_DESCRIPTION => [],
                    WidgetPage::FIELD_RETURN_VALUE => [],
                ]
            ]
        ]
    ];