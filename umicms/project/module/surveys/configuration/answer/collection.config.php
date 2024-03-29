<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\surveys\model\object\Answer;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\surveys\model\collection\AnswerCollection',

    'handlers' => [
        'admin' => 'surveys.answer'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/surveys/configuration/answer/form/base.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/surveys/configuration/answer/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.answer' => 'collection.answer', 'collection' => 'collection'
    ],

    ICmsCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        Answer::FIELD_SURVEY => [],
        Answer::FIELD_VOTES => [],
    ]
];