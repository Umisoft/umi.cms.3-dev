<?php

use umicms\project\Environment;

return [
    'umi_rockband' => require(Environment::$directoryPublic . '/umi_rockband/configuration/project.config.php'),
    'demo-xslt' => require(Environment::$directoryPublic . '/demo-xslt/configuration/project.config.php'),
    'demo-twig' => require(Environment::$directoryPublic . '/demo-twig/configuration/project.config.php'),
    'demo-php' => require(Environment::$directoryPublic . '/demo-php/configuration/project.config.php'),
    'umi_docs' =>  require(Environment::$directoryPublic . '/umi_docs/configuration/project.config.php')
];