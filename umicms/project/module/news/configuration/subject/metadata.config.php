<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\Environment;
use umicms\project\module\news\model\object\NewsSubject;

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/pageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'news_subject'
        ],
        'fields'     => [
            NewsSubject::FIELD_NEWS => [
                'type'         => IField::TYPE_MANY_TO_MANY,
                'target'       => 'newsItem',
                'bridge'       => 'newsItemSubject',
                'relatedField' => 'subject',
                'targetField'  => 'newsItem',
            ]
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\news\model\object\NewsSubject',
                'fields'      => [
                    NewsSubject::FIELD_NEWS,
                ]
            ]
        ]
    ]
);
