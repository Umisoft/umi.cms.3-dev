<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\toolbox;

/**
 * Конфигурация для регистрации набора инструментов.
 */
return [
    'name'    => CmsOrmTools::NAME,
    'class'        => __NAMESPACE__ . '\CmsOrmTools',
    'awareInterfaces' => [
        'umi\orm\collection\ICollectionManagerAware',
        'umi\orm\manager\IObjectManagerAware',
        'umi\orm\metadata\IMetadataManagerAware',
        'umi\orm\persister\IObjectPersisterAware',
        'umicms\orm\dump\IObjectDumpAware'
    ],
    'services' => [
        'umi\orm\collection\ICollectionManager',
        'umi\orm\manager\IObjectManager',
        'umi\orm\metadata\IMetadataManager',
        'umi\orm\persister\IObjectPersister',

        'umicms\orm\dump\ICmsObjectExporter',
        'umicms\orm\dump\ICmsObjectImporter'
    ]
];