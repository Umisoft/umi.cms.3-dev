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
use umicms\project\module\blog\api\object\BlogComment;

return [
    'options' => [
        'dictionaries' => [
            'project.site.blog.comment'
        ]
    ],
    'attributes' => [
        'method' => 'post'
    ],
    'elements' => [
        BlogComment::FIELD_IDENTIFY => [
            'type' => Hidden::TYPE_NAME,
            'label' => BlogComment::FIELD_IDENTIFY,
            'options' => [
                'dataSource' => BlogComment::FIELD_IDENTIFY
            ],
        ]
    ],
    BaseFormWidget::INPUT_REDIRECT_URL => [
        'type' => Hidden::TYPE_NAME
    ],
    'submit' => [
        'type' => Submit::TYPE_NAME,
        'label' => 'Publish'
    ]

];