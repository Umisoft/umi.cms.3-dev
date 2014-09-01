<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout\button;

/**
 * Split-кнопка для тулбара.
 * @see http://foundation.zurb.com/docs/components/split_buttons.html
 */
class SplitButton extends Button
{
    /**
     * @var string $type тип кнопки
     */
    public $type = 'splitButton';
    /**
     * @var array $attributes атрибуты кнопки
     */
    public $attributes = [
        'class' => 'button split',
        'hasIcon' => true
    ];
}
 