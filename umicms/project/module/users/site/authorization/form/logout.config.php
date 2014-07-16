<?php

use umi\form\element\Hidden;
use umi\form\element\Submit;
use umicms\hmvc\widget\BaseFormWidget;

return [

    'options' => [
        'dictionaries' => [
            'project.site.users.authorization'
        ],
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
            'label' => 'Log out'
        ]
    ]
];