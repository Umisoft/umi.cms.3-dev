<?php
use umi\dbal\toolbox\DbalTools;
use umi\orm\toolbox\ORMTools;
use umicms\model\toolbox\ModelTools;

return [
    DbalTools::NAME => [
        'servers' => require(__DIR__ . '/db.config.php')
    ],
    ModelTools::NAME => [
        'factories' => [
            'modelEntity' => [
                'tableNamePrefix' => 'rock_'
            ]
        ]
    ],
    ORMTools::NAME => [
        'factories' => [
            'metadata' => [
                'dataSourceNamePrefix' => 'rock_'
            ]
        ]
    ]
];