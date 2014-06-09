<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\purifier;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для поддержки очистителя контента.
 */
trait TPurifierAware
{
    /**
     * @var IPurifier $traitPurifier
     */
    private $traitPurifier;

    /**
     * @see IPurifierAware::setPurifier()
     */
    public function setPurifier(IPurifier $purifier)
    {
        $this->traitPurifier = $purifier;
    }

    /**
     * Возвращает очиститель контента.
     * @throws RequiredDependencyException если очиститель не был внедрен
     * @return IPurifier
     */
    protected function getPurifier()
    {
        if (!$this->traitPurifier) {
            throw new RequiredDependencyException(sprintf(
                'Purifier is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitPurifier;
    }

    /**
     * Очищает HTML от возможных XSS.
     * @param string $string входная строка
     * @param array $options опции для конфигурирования
     * @return string
     */
    public function purifyHtml($string, array $options = [])
    {
        return $this->getPurifier()->purify($string, $options);
    }
}
 