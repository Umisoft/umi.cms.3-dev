<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\widget;

use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\i18n\CmsLocalesService;
use umicms\project\admin\AdminApplication;
use umicms\project\admin\rest\RestApplication;
use umicms\project\Environment;

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

        $baseResourceUrl = Environment::$baseUrl;
        $version = CMS_VERSION;

        $modulesInfo = json_encode($this->getModulesInfo());

        $result = <<<EOF
<link rel="stylesheet" type="text/css" href="{$baseResourceUrl}/umi-admin/sitePanel/styles/styles.css?version={$version}">
<script>
    window.UmiSettings = {
        "baseURL": "{$baseAdminUrl}",
        "baseApiURL": "{$baseRestUrl}",
        "i18n": {
            "adminPanelLabel": "{$adminPanelLabel}",
            "logoutLabel": "{$logoutLabel}"
        },
        "modules": {$modulesInfo}
    }
</script>
<script src="{$baseResourceUrl}/umi-admin/development/module/eip/main.js?version={$version}"></script>
EOF;
        return $this->createPlainResult($result);
    }

    /**
     * Возвращает информацию о доступных модулях
     * @return array
     */
    private function getModulesInfo()
    {
        $dispatcher = $this->getContext()->getDispatcher();

        /**
         * @var RestApplication $restApplication
         */
        $restApplication = $dispatcher->getComponentByPath('project.admin.rest');
        $modules = [];

        foreach ($restApplication->getChildComponentNames() as $componentName) {
            /**
             * @var AdminComponent $component
             */
            $component = $restApplication->getChildComponent($componentName);

            if (!$component->isSkippedInDock() && $dispatcher->checkPermissions($restApplication, $component)) {

                $modules[] = [
                    'name' => $componentName,
                    'displayName' => $component->translate('component:' . $componentName . ':displayName')
                ];
            }
        }

        return $modules;
    }

    /**
     * Возвращает сервис для работы с локалями
     * @throws RequiredDependencyException если сервис не был внедрен
     * @return CmsLocalesService
     */
    private function getLocalesService()
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
 