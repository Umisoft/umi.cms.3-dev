<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout\button;


/**
 * Кнопка для тулбара с выпадающим списком.
 */
class DropdownButton extends Button
{
    /**
     * @var string $type тип кнопки
     */
    public $type = 'dropdownButton';
    /**
     * @var array $attributes атрибуты кнопки
     */
    public $attributes = [
        'class' => 'umi-button umi-toolbar-create-button',
        'hasIcon' => true
    ];

}
 