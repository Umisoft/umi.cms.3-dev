<?php

use umicms\project\Environment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/scheme/simple.hierarchic.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/common.page.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/active.config.php',
    require Environment::$directoryCmsProject . '/configuration/scheme/recoverable.config.php'
);