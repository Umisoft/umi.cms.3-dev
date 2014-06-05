<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout\button\behaviour;

use umicms\project\admin\layout\button\Choice;

/**
 * Обработчик элементов выбора.
 */
class ChoicesBehaviour extends Behaviour
{
    /**
     * @var Choice[] $choices
     */
    protected $choices;

    /**
     * Добавляет элемент выбора
     * @param string $name
     * @param Choice $choice
     * @return $this
     */
    public function addChoice($name, Choice $choice)
    {
        $this->choices[$name] = $choice;

        return $this;
    }

    /**
     * Удаляет вариант выбора
     * @param string $name
     * @return $this
     */
    public function removeChoice($name)
    {
        unset($this->choices[$name]);

        return $this;
    }

    /**
     * Возвращает информацию об обработчике.
     * @return array
     */
    public function build()
    {
        $params = $this->params;

        $params['choices'] = [];
        foreach ($this->choices as $choice) {
            $params['choices'][] = $choice->build();
        }

        return $params;
    }

}
 