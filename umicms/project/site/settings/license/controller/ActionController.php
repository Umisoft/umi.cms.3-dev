<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\settings\license\controller;

use umi\config\entity\IConfig;
use umi\form\element\Text;
use umi\form\IForm;
use umicms\hmvc\component\admin\settings\ActionController as BaseActionController;
use umicms\project\module\service\model\ServiceModule;

/**
 * Контроллер действий над настройками.
 */
class ActionController extends BaseActionController
{
    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form, IConfig $config)
    {
        $licenseKey = $form->get('licenseKey');
        if ($licenseKey instanceof Text) {
            $licenseKey = $licenseKey->getValue();
        }

        $domain = $form->get('defaultDomain');
        if ($domain instanceof Text) {
            $domain = $domain->getValue();
        }

        if (
            (!empty($licenseKey) && is_string($licenseKey)) &&
            (!empty($domain) && is_string($domain))
        ) {
            /** @var ServiceModule $serviceModule */
            $serviceModule = $this->getModuleByClass(ServiceModule::className());
            $serviceModule->license()->activate($licenseKey, $domain, $config);
        }

        $config->set('licenseKey', '');
    }
}
 