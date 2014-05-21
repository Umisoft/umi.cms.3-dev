<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\structure\api\object\InfoBlock;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\structure\api\collection\InfoBlockCollection',

    'handlers' => [
        'admin' => 'structure.infoblock',
        'site' => 'structure.infoblock'
    ],
    'forms' => [
        InfoBlock::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/infoblock/form/infoblock.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/infoblock/form/infoblock.create.config.php}'
        ],
    ],
    'dictionaries' => [
        'collection.infoblock', 'collection'
    ]
];