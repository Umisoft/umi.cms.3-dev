<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout\button\behaviour;

/**
 * Класс обработчика кнопки
 */
class Behaviour
{
    /**
     * @var array $params параметры обработчика
     */
    protected $params;

    /**
     * Конструктор.
     * @param string $name имя обработчика
     * @param array $params параметры обработчика
     */
    public function __construct($name, array $params = [])
    {
        $this->params = $params;
        $this->params['name'] = $name;
    }

    /**
     * Добавляет произвольный параметр обработчика
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * Удаляет параметр обработчика
     * @param string $name
     * @return $this
     */
    public function removeParam($name)
    {
        unset($this->params[$name]);

        return $this;
    }

    /**
     * Возвращает информацию об обработчике.
     * @return array
     */
    public function build()
    {
        return $this->params;
    }
}
 