<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
     * @see ICmsObject::getEditLink()
     */
    public function getEditLink()
    {
        if (!$this->traitEditLink) {
            /** @noinspection PhpParamsInspection */
            $this->traitEditLink = $this->getUrlManager()->getObjectEditLinkUrl($this);
        }

        return $this->traitEditLink;
    }

}
 