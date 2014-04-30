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
use umi\i18n\TLocalesAware;
use umicms\project\admin\component\AdminComponent;

/**
 * Приложение административной панели.
 */
class AdminApplication extends AdminComponent implements ILocalesAware
{
    use TLocalesAware;

    const CURRENT_LOCALE_COOKIE_NAME = 'locale';

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $currentLocale = $request->cookies->get(self::CURRENT_LOCALE_COOKIE_NAME, $this->getCurrentLocale());
        $this->setCurrentLocale($currentLocale);
    }
}
 