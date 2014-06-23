<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\http\Request;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umi\i18n\TLocalesAware;
use umicms\exception\RequiredDependencyException;
use umicms\i18n\CmsLocalesService;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Приложение административной панели.
 */
class AdminApplication extends AdminComponent implements ILocalesAware
{
    use TLocalesAware;

    /**
     * Имя куки, для выбранной пользователем локали.
     */
    const CURRENT_LOCALE_COOKIE_NAME = 'locale';

    /**
     * @var CmsLocalesService $localesService сервис для работы с локалями
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
 