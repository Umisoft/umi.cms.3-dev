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
     * @see TUrlManagerAware::getUrlManager()
     * @return IUrlManager
     */
    abstract protected function getUrlManager();

    /**
     * @see ICmsPage::getPageUrl()
     */
    public function getPageUrl($isAbsolute = false)
    {
        /** @noinspection PhpParamsInspection */
        return $this->getUrlManager()->getSitePageUrl($this, $isAbsolute);
    }
}
 