<?php

use umi\form\element\Checkbox;
use umicms\project\module\surveys\model\object\Survey;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.create.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.survey' => 'collection.survey'
            ]
        ],

        'elements' => [
            'contents' => [
                'elements' => [
                    Survey::FIELD_MULTIPLE_CHOICE => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => Survey::FIELD_MULTIPLE_CHOICE,
                        'options' => [
                            'dataSource' => Survey::FIELD_MULTIPLE_CHOICE
                        ]
                    ]
                ]
            ]
        ]
    ]
);