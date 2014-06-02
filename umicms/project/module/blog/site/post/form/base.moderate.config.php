<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Hidden;
use umi\form\element\Submit;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\api\object\BlogPost;

return [
    'options' => [
        'dictionaries' => [
            'project.site.blog.post'
        ]
    ],
    'attributes' => [
        'method' => 'post'
    ],
    'elements' => [
        BlogPost::FIELD_IDENTIFY => [
            'type' => Hidden::TYPE_NAME,
            'label' => BlogPost::FIELD_IDENTIFY,
            'options' => [
                'dataSource' => BlogPost::FIELD_IDENTIFY
            ],
        ],
        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Moderate'
        ]
    ]
];