<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\WidgetPage;
use umi\form\element\Checkbox;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;

return [

    'options' => [
        'dictionaries' => [
            'collection.structure', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                WidgetPage::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_DISPLAY_NAME
                    ],
                ],
                WidgetPage::FIELD_PAGE_LAYOUT => [
                    'type' => Select::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_LAYOUT,
                    'options' => [
                        'choices' => [
                            null => 'Default or inherited layout'
                        ],
                        'lazy' => true,
                        'dataSource' => WidgetPage::FIELD_PAGE_LAYOUT
                    ],
                ],
                WidgetPage::FIELD_IN_MENU => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => WidgetPage::FIELD_IN_MENU,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_IN_MENU
                    ],
                ],
                WidgetPage::FIELD_SUBMENU_STATE => [
                    'type' => Select::TYPE_NAME,
                    'label' => WidgetPage::FIELD_SUBMENU_STATE,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_SUBMENU_STATE,
                        'choices' => [
                            WidgetPage::SUBMENU_NEVER_SHOWN => 'neverShown',
                            WidgetPage::SUBMENU_CURRENT_SHOWN => 'currentShown',
                            WidgetPage::SUBMENU_ALWAYS_SHOWN => 'alwaysShown'
                        ]
                    ],
                ]
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'meta',
            'elements' => [
                WidgetPage::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_H1,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PAGE_H1
                    ],
                ],
                WidgetPage::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_META_TITLE,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PAGE_META_TITLE
                    ],
                ],
                WidgetPage::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_META_KEYWORDS,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                WidgetPage::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_META_DESCRIPTION,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [

                WidgetPage::FIELD_PATH => [
                    'type' => Text::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PATH,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PATH
                    ]
                ],

                WidgetPage::FIELD_DESCRIPTION => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_DESCRIPTION,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_DESCRIPTION
                    ]
                ],

                WidgetPage::FIELD_RETURN_VALUE => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_RETURN_VALUE,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_RETURN_VALUE
                    ]
                ],

                WidgetPage::FIELD_PARAMETERS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PARAMETERS,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PARAMETERS
                    ]
                ],
                WidgetPage::FIELD_TWIG_EXAMPLE => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_TWIG_EXAMPLE,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_TWIG_EXAMPLE
                    ]
                ],
                WidgetPage::FIELD_PHP_EXAMPLE => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PHP_EXAMPLE,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PHP_EXAMPLE
                    ]
                ],
                WidgetPage::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => WidgetPage::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => WidgetPage::FIELD_PAGE_CONTENTS
                    ]
                ],

                'secondContent' => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => 'secondContent',
                    'options' => [
                        'dataSource' => 'secondContents'
                    ]
                ],
            ]
        ]
    ]
];