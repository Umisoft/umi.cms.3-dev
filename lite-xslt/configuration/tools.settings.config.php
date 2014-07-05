<?php
use umi\dbal\toolbox\DbalTools;

return [
    DbalTools::NAME => [
        'servers' => require (__DIR__ . '/../../configuration/db.config.php')
    ]
];