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