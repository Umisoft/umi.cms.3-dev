<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\exception;

/**
 * Исключение возникшее в ходе установки системы.
 */
class RuntimeException extends \RuntimeException
{
    /**
     * @var string $overlay
     */
    protected $overlay;

    /**
     * {@inheritdoc}
     */
    public function __construct($message = "", $overlay = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->overlay = $overlay;
    }

    /**
     * @return null|string возвращает значение свойства
     */
    public function getOverlay()
    {
        return $this->overlay;
    }
}
 