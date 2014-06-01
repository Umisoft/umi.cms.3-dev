<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\i18n;

/**
 * Базовый класс локали.
 */
abstract class BaseLocale
{
    /**
     * @var string $id идентификатор
     */
    protected $id;

    /**
     * Конструктор.
     * @param string $id идентификатор
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает идентификатор.
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
 