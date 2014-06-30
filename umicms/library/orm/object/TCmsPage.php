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

    /**
     * @see ICmsPage::getHeader()
     */
    public function getHeader()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->h1 ? $this->h1 : $this->displayName;
    }
}
 