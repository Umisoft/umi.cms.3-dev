<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\megaindex\layout;

use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\action\Action;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\hmvc\url\IUrlManager;
use umicms\project\module\seo\model\MegaindexModel;

/**
 * Билдер сетки административного компонента Megaindex.
 */
class MegaindexComponentLayout extends AdminComponentLayout
{
    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        $this->component = $component;

        $this->addAction('getResource', new Action(
            $urlManager->getAdminComponentResourceUrl($this->component) . '/action/{id}')
        );

        $this->dataSource = [
            'type' => 'static',
            'objects' => $this->getChildActionResources()
        ];

        if ($this->isIssetSettings()) {
            $this->addSideBarControl('menu',  new AdminControl($this->component));
        }

        $this->configureEmptyContextControls();

        if ($this->isIssetSettings()) {
            $this->configureDynamicControl();
        }
    }

    /**
     * Возвращает массив доступных action.
     * @return array
     */
    public function getChildActionResources()
    {
        $result = [];

        foreach ($this->component->getQueryActions() as $name => $action) {
            $result[] = [
                'id' => $name,
                'displayName' => $this->component->translate(
                        'action:' . $name . ':displayName'
                    )
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureEmptyContextControls()
    {
        if ($this->isIssetSettings()) {
            $control = new AdminControl($this->component);
            $control->params['slug'] = 'siteAnalyze';

            $this->addEmptyContextControl('redirect', $control);
        } else {
            $control = new AdminControl($this->component);
            $control->params['content'] = $this->component->translate('The required settings are not set');

            $this->addEmptyContextControl('empty', $control);
        }
    }

    /**
     * Конфигурирует динамический контрол.
     */
    private function configureDynamicControl()
    {
        $dynamicControl = new AdminControl($this->component);
        $dynamicControl->params['action'] = 'getResource';
        $dynamicControl->labels = [
            'Rows on page' => $this->component->translate("Rows on page"),
            'No data' => $this->component->translate("No data")
        ];
        $this->addSelectedContextControl('dynamic', $dynamicControl);
    }

    /**
     * Проверяет, что заданы все необходимые настройки
     * @return bool
     */
    private function isIssetSettings()
    {
        $login = $this->component->getSetting(MegaindexModel::MEGAINDEX_LOGIN);
        $password = $this->component->getSetting(MegaindexModel::MEGAINDEX_PASSWORD);
        $siteUrl = $this->component->getSetting(MegaindexModel::MEGAINDEX_SITE_URL);

        if (!empty($login) && !empty($password) && !empty($siteUrl)) {
            return true;
        }

        return false;
    }
}
 