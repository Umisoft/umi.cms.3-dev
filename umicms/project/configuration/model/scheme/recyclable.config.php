<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Types\Type;

/**
 * Схема колонок для коллекций,
 * поддерживающих удаление объектов в корзину.
 */
return [
    'columns' => [
        'trashed'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0,
                'notnull' => true
            ]
        ]
    ]
];