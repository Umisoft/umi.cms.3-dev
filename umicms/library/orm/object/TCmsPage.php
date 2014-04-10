<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\object;

use umicms\hmvc\url\IUrlManager;

/**
 * Трейт для поддержки страниц.
 */
trait TCmsPage
{
    /**
     * @var string $traitPageUrl URL страницы на сайте
     */
    private $traitPageUrl;

     /**
     * @see TUrlManagerAware::getUrlManager()
     * @return IUrlManager
     */
    abstract protected function getUrlManager();

    /**
     * @see ICmsPage::getPageUrl()
     */
    public function getPageUrl()
    {
        if (!$this->traitPageUrl) {
            /** @noinspection PhpParamsInspection */
            $this->traitPageUrl = $this->getUrlManager()->getSitePageUrl($this);
        }

        return $this->traitPageUrl;
    }
}
 