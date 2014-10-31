<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Схема таблицы для иерархической коллекции объектов, имеющих страницу на сайте
 */
return array_replace_recursive(
    require __DIR__ . '/hierarchicCollection.config.php',
    require __DIR__ . '/page.common.php',
    require __DIR__ . '/active.config.php',
    require __DIR__ . '/recyclable.config.php'
);