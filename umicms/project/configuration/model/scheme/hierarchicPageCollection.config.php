<?php

/**
 * Схема таблицы для иерархической коллекции объектов, имеющийх страницу на сайте
 */
return array_replace_recursive(
    require __DIR__ . '/hierarchicCollection.config.php',
    require __DIR__ . '/page.common.php',
    require __DIR__ . '/active.config.php',
    require __DIR__ . '/recyclable.config.php'
);