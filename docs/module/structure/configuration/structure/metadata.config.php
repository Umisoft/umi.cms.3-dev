<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\docs\module\structure\model\object\WidgetPage;
use umi\orm\metadata\field\IField;

return
    [
        'fields'     => [
            WidgetPage::FIELD_PARAMETERS => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'parameters',
            ],

            WidgetPage::FIELD_DESCRIPTION => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'description',
            ]
        ],
        'types'      => [
            WidgetPage::TYPE           => [
                'objectClass' => 'project\docs\module\structure\model\object\WidgetPage',
                'fields'      => [
                    WidgetPage::FIELD_PARAMETERS => [],
                    WidgetPage::FIELD_DESCRIPTION => [],
                ]
            ]
        ]
    ];