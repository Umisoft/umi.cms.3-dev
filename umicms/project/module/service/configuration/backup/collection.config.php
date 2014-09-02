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

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\service\model\collection\BackupCollection',
    'handlers' => [
        'admin' => 'service.backup',
    ],
    'dictionaries' => [
        'collection.serviceBackup' => 'collection.serviceBackup', 'collection' => 'collection'
    ],
    'settings' => '{#partial:~/project/module/service/configuration/backup/collection.settings.config.php}'
];