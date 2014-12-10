<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Select;
use umi\validation\IValidatorFactory;
use umicms\project\module\forum\model\object\ForumTheme;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.create.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.forumTheme' => 'collection.forumTheme'
            ]
        ],
        'elements' => [
            'contents' => [
                'elements' => [
                    ForumTheme::FIELD_CONFERENCE => [
                        'type' => Select::TYPE_NAME,
                        'label' => ForumTheme::FIELD_CONFERENCE,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => ForumTheme::FIELD_CONFERENCE,
                            'validators' => [
                                IValidatorFactory::TYPE_REQUIRED => []
                            ]
                        ]
                    ],
                    ForumTheme::FIELD_AUTHOR => [
                        'type' => Select::TYPE_NAME,
                        'label' => ForumTheme::FIELD_AUTHOR,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => ForumTheme::FIELD_AUTHOR,
                            'validators' => [
                                IValidatorFactory::TYPE_REQUIRED => []
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
);