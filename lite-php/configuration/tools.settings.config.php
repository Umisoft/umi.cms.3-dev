<?php
use umi\dbal\toolbox\DbalTools;
use umi\orm\toolbox\ORMTools;
use umicms\model\toolbox\ModelTools;

return [
    DbalTools::NAME => [
        'servers' => require (__DIR__ . '/../../configuration/db.config.php')
    ],
    ModelTools::NAME => [
        'factories' => [
            'modelEntity' => [
                'tableNamePrefix' => 'demo_'
            ]
        ]
    ],
    ORMTools::NAME => [
        'factories' => [
            'metadata' => [
                'dataSourceNamePrefix' => 'demo_'
            ]
        ]
    ]
];