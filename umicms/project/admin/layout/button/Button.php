<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout\button;

use umicms\project\admin\layout\button\behaviour\Behaviour;

/**
 * Простая кнопка для тулбара.
 */
class Button
{
    /**
     * @var string $type тип кнопки
     */
    public $type = 'button';
    /**
     * @var array $attributes атрибуты кнопки
     */
    public $attributes = [
        'hasIcon' => true,
        'class' => 'umi-button-icon-32 umi-light-bg'
    ];

    /**
     * @var Behaviour $behaviour обработчик кнопки
     */
    public $behaviour;
    /**
     * @var string $label лэйбл кнопки
     */
    protected $label;
    /**
     * @var array $states состояния кнопки
     */
    protected $states = [];

    /**
     * Конструктор.
     * @param string $label лэйбл кнопки
     * @param Behaviour $behaviour обработчик кнопки
     */
    public function __construct($label, Behaviour $behaviour)
    {
        $this->behaviour = $behaviour;

        $this->attributes['label'] = $label;
    }

    /**
     * Добавить состояние кнопке.
     * @param string $stateName имя состояния
     * @param array $params параметры состояния
     * @return $this
     */
    public function addState($stateName, array $params)
    {
        $this->states[$stateName] = $params;
        return $this;
    }

    /**
     * Возвращает информацию о кнопке.
     * @return array
     */
    public function build()
    {
        $result = [
            'type'       => $this->type,
            'behaviour'  => $this->behaviour->build(),
            'attributes' => $this->attributes
        ];

        if ($this->states) {
            $result['attributes']['states'] = $this->states;
        }

        return $result;
    }
}
 