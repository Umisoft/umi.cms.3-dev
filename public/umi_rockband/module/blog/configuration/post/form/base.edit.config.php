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

return [
    'elements' => [
        'contents' => [
            'elements' => [
                'imageMain' => [
                    'type' => Text::TYPE_NAME,
                    'label' => 'imageMain',
                    'options' => [
                        'dataSource' => 'imageMain'
                    ]
                ],
                'imageInternal' => [
                    'type' => Text::TYPE_NAME,
                    'label' => 'imageInternal',
                    'options' => [
                        'dataSource' => 'imageInternal'
                    ]
                ]
            ]
        ]
    ]
];
