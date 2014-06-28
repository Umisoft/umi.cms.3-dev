<?php

/**
 * Схема таблицы для иерархической коллекции объектов, имеющийх страницу на сайте
 */
return array_merge_recursive(
    require __DIR__ . '/collection.config.php',
    require __DIR__ . '/page.common.php',
    require __DIR__ . '/active.config.php',
    require __DIR__ . '/recyclable.config.php'
);