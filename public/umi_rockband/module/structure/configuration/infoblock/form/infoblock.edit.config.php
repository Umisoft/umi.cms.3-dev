<?php
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umicms\form\element\File;
use umicms\form\element\Image;
use umicms\form\element\Wysiwyg;

return [
    'elements' => [
        'additional' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'additional',
            'elements' => [
                'firstImage' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'firstImage',
                    'options' => [
                        'dataSource' => 'firstImage'
                    ]
                ],
                'secondImage' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'secondImage',
                    'options' => [
                        'dataSource' => 'secondImage'
                    ]
                ],
                'file' => [
                    'type' => File::TYPE_NAME,
                    'label' => 'file',
                    'options' => [
                        'dataSource' => 'file'
                    ]
                ],
                'secondContents' => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => 'secondContents',
                    'options' => [
                        'dataSource' => 'secondContents'
                    ]
                ],
                'simpleText' => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => 'simpleText',
                    'options' => [
                        'dataSource' => 'simpleText'
                    ]
                ]
            ]
        ]
    ]
];
