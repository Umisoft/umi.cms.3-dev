<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Text;
use umi\validation\IValidatorFactory;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site',
            'project.admin.rest.settings.site.license'
        ]
    ],

    'elements' => [
        'defaultDomain' => [
            'type' => Text::TYPE_NAME,
            'label' => 'defaultDomain',
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'dataSource' => 'defaultDomain'
            ]
        ],
        'licenseKey' => [
            'type' => Text::TYPE_NAME,
            'label' => 'licenseKey',
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'dataSource' => 'licenseKey'
            ]
        ],
        'domainKey' => [
            'type' => Text::TYPE_NAME,
            'label' => 'domainKey',
            'options' => [
                'dataSource' => 'domainKey'
            ]
        ]
    ]

];