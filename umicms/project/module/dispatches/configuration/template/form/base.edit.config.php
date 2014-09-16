<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\dispatches\model\object\Template;

return [

    'options' => [
        'dictionaries' => [
            'collection.dispatchTemplate', 'collection', 'form'
        ]
    ],

    'elements' => [
        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Template::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Template::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Template::FIELD_DISPLAY_NAME
                    ],
                ],
				Template::FIELD_PATHTEMPLATE => [
                    'type' => Text::TYPE_NAME,
                    'label' => Template::FIELD_PATHTEMPLATE,
                    'options' => [
                        'dataSource' => Template::FIELD_PATHTEMPLATE
                    ],
                ]
            ]
        ]
    ]
];