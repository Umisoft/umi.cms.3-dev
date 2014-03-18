<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
 