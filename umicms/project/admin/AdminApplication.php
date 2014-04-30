<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\http\Request;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umi\i18n\TLocalesAware;
use umicms\exception\RequiredDependencyException;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\component\AdminComponent;

/**
 * Приложение административной панели.
 */
class AdminApplication extends AdminComponent implements ILocalesAware
{
    use TLocalesAware;

    const CURRENT_LOCALE_COOKIE_NAME = 'locale';

    /**
     * @var CmsLocalesService $traitLocalesService сервис для работы с локалями
     */
    private $localesService;

    /**
     * {@inheritdoc}
     */
    public function setLocalesService(ILocalesService $localesService)
    {
        $this->localesService = $localesService;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $defaultLocale = $this->getLocalesService()->getDefaultAdminLocaleId();
        $currentLocale = $request->cookies->get(self::CURRENT_LOCALE_COOKIE_NAME, $defaultLocale);

        $this->setCurrentLocale($currentLocale);
        $this->setDefaultLocale($defaultLocale);
    }

    /**
     * Возвращает сервис для работы с локалями
     * @throws RequiredDependencyException если сервис не был внедрен
     * @return CmsLocalesService
     */
    protected function getLocalesService()
    {
        if (!$this->localesService) {
            throw new RequiredDependencyException(sprintf(
                'Locales service is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->localesService;
    }
}
 