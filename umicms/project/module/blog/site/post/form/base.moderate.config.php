<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Hidden;
use umi\form\element\Submit;
use umicms\hmvc\widget\BaseFormWidget;

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
        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Moderate'
        ]
    ]
];