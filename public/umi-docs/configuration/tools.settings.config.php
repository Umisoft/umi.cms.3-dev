<?php
use umi\dbal\toolbox\DbalTools;
use umi\orm\toolbox\ORMTools;
use umicms\model\toolbox\ModelTools;

return [
    DbalTools::NAME => [
        'servers' => require (__DIR__ . '/db.config.php')
    ],
    ModelTools::NAME => [
        'factories' => [
            'modelEntity' => [
                'tableNamePrefix' => 'docs_'
            ]
        ]
    ],
    ORMTools::NAME => [
        'factories' => [
            'metadata' => [
                'dataSourceNamePrefix' => 'docs_'
            ]
        ]
    ]
];