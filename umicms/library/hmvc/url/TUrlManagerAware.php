<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\url;

use umicms\exception\RequiredDependencyException;

/**
 * Трейт для поддержки работы с URL-менеджером.
 */
trait TUrlManagerAware
{

    /**
     * @var IUrlManager $traitUrlManager URL-менеджер
     */
    private $traitUrlManager;

    /**
     * @see IUrlManagerAware::setUrlManager()
     */
    public function setUrlManager(IUrlManager $urlManager)
    {
        $this->traitUrlManager = $urlManager;
    }

    /**
     * Возвращает URL-менеджер.
     * @throws RequiredDependencyException если менеджер коллекций объектов не установлен
     * @return IUrlManager
     */
    protected function getUrlManager()
    {
        if (!$this->traitUrlManager) {
            throw new RequiredDependencyException(sprintf(
                'URL manager is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitUrlManager;
    }

}
 