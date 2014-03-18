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
 * Трейт для поддержки страниц.
 */
trait TCmsPage
{
    use TUrlManagerAware;

    /**
     * @var string $url URL новости
     */
    private $traitUrl;

    /**
     * @see ICmsPage::getPageUrl()
     */
    public function getPageUrl()
    {
        if (!$this->traitUrl) {
            /** @noinspection PhpParamsInspection */
            $this->traitUrl = $this->getUrlManager()->getSitePageUrl($this);
        }

        return $this->traitUrl;
    }
}
 