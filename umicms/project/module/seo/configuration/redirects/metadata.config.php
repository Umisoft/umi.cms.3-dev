<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\module\seo\model\object\Redirect;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'redirects'
        ],
        'fields' => [
            Redirect::FIELD_SOURCE_PATTERN => [
                'type' => Text::TYPE_NAME,
                'columnName' => 'source_pattern',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED    => [],
                ],
            ],
            Redirect::FIELD_TARGET_PATTERN => [
                'type' => Text::TYPE_NAME,
                'columnName' => 'target_pattern',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED    => [],
                ],
            ],
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\project\module\seo\model\object\Redirect',
                'fields' => [
                    Redirect::FIELD_SOURCE_PATTERN => [],
                    Redirect::FIELD_TARGET_PATTERN => [],
                ],
            ],
        ],
    ]
);
