<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Submit;
use umi\form\element\Text;
use umicms\orm\object\ICmsPage;

return [

    'options' => [
        'dictionaries' => [
            'collection' => 'collection', 'form' => 'form'
        ]
    ],
    'elements' => [
        ICmsPage::FIELD_PAGE_SLUG => [
            'type' => Text::TYPE_NAME,
            'label' => ICmsPage::FIELD_PAGE_SLUG,
            'options' => [
                'dataSource' => ICmsPage::FIELD_PAGE_SLUG
            ],
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Change'
        ]
    ]
];