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

use umicms\hmvc\widget\BaseCmsWidget;

/**
 * Виджет вывода верхней панели сайта.
 */
class TopBarWidget extends BaseCmsWidget
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $urlManager = $this->getUrlManager();
        $baseAdminUrl = $urlManager->getBaseAdminUrl();
        $baseRestUrl = $urlManager->getBaseRestUrl();

        $logoutLabel = $this->translate('Logout');
        $adminPanelLabel = $this->translate('Administrative panel');

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
}
 