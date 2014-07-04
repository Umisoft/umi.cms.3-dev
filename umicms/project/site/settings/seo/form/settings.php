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
use umicms\project\site\SiteApplication;
use umicms\filter\HtmlPurifier;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        SiteApplication::SETTING_DEFAULT_DESCRIPTION => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_DESCRIPTION,
            'options' => [
                'dataSource' => SiteApplication::SETTING_DEFAULT_DESCRIPTION,
                'filters' => [
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],
        SiteApplication::SETTING_DEFAULT_KEYWORDS => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_KEYWORDS,
            'options' => [
                'dataSource' => SiteApplication::SETTING_DEFAULT_KEYWORDS,
                'filters' => [
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],
        SiteApplication::SETTING_DEFAULT_TITLE => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_TITLE,
            'options' => [
                'dataSource' => SiteApplication::SETTING_DEFAULT_TITLE,
                'filters' => [
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ],
        SiteApplication::SETTING_TITLE_PREFIX => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_TITLE_PREFIX,
            'options' => [
                'dataSource' => SiteApplication::SETTING_TITLE_PREFIX,
                'filters' => [
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ]
            ]
        ]
    ]

];