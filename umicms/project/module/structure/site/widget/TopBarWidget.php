<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site\widget;

use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\AdminApplication;

/**
 * Виджет вывода верхней панели сайта.
 */
class TopBarWidget extends BaseCmsWidget implements ILocalesAware
{
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
    public function __invoke()
    {
        $urlManager = $this->getUrlManager();
        $baseAdminUrl = $urlManager->getBaseAdminUrl();
        $baseRestUrl = $urlManager->getBaseRestUrl();

        $defaultAdminLocale = $this->getLocalesService()->getDefaultAdminLocaleId();
        $currentAdminLocale = $this->getContext()
                ->getDispatcher()
                ->getCurrentRequest()->cookies->get(AdminApplication::CURRENT_LOCALE_COOKIE_NAME, $defaultAdminLocale);

        $logoutLabel = $this->translate('Logout', [], $currentAdminLocale);
        $adminPanelLabel = $this->translate('Administrative panel', [], $currentAdminLocale);

        return <<<EOF
<link rel="stylesheet" type="text/css" href="/umi-admin/sitePanel/styles/styles.css?version=1">
<script>
    window.UmiSettings = {
        "baseURL": "{$baseAdminUrl}",
        "baseApiURL": "{$baseRestUrl}",
        "i18n": {
            "adminPanelLabel": "{$adminPanelLabel}",
            "logoutLabel": "{$logoutLabel}"
        }
    }
</script>
<script src="/umi-admin/sitePanel/main.js?version=1"></script>
EOF;
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
 