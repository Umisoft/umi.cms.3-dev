<?php

/**
 * Схема таблицы для простой коллекции объектов, которые имеют страницу на сайте
 */
return array_replace_recursive(
    require __DIR__ . '/collection.config.php',
    require __DIR__ . '/page.common.php',
    require __DIR__ . '/active.config.php',
    require __DIR__ . '/recyclable.config.php'
);