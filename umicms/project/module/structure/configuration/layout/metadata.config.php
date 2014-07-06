<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\Environment;
use umicms\project\module\structure\model\object\Layout;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'layout'
        ],
        'fields'     => [
            Layout::FIELD_FILE_NAME    => [
                'type' => IField::TYPE_STRING,
                'columnName' => 'file_name',
                'filters'       => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators'    => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
            ]
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\structure\model\object\Layout',
                'fields'      => [
                    Layout::FIELD_FILE_NAME => []
                ]
            ]
        ]
    ]
);
