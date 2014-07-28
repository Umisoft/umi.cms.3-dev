<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\installer;

use umicms\install\exception\RuntimeException;

/**
 * Проверка серверных зависимостей.
 */
class Rule
{
    /**
     * @var array $rules правила проверки
     */
    private $rules = [];

    /**
     * Конструктор.
     * @param array $rules правила
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Запускает проверку.
     * @throws RuntimeException возникает при отсутствии требуемых расширений
     */
    public function check()
    {
        foreach ($this->rules as $rule) {
            if (!extension_loaded($rule)) {
                throw new RuntimeException($rule . ' обязателено для системы.');
            }
        }
    }
}
 