<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use umicms\hmvc\url\TUrlManagerAware;

/**
 * Трейт для поддержки объектов.
 */
trait TCmsObject
{
    use TUrlManagerAware;

    /**
     * @var string $traitEditLink ссылка на редактирование объекта
     */
    private $traitEditLink;

    /**
     * Возаращает имя класса объекта.
     * @return string
     */
    public static function className() {
        return get_called_class();
    }

    /**
     * @see ICmsObject::getEditLink()
     */
    public function getEditLink($isAbsolute = false)
    {
        if (!$this->traitEditLink) {
            /** @noinspection PhpParamsInspection */
            $this->traitEditLink = $this->getUrlManager()->getObjectEditLinkUrl($this, $isAbsolute);
        }

        return $this->traitEditLink;
    }

}
 