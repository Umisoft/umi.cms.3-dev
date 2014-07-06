<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\settings;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\http\Response;
use umicms\hmvc\component\admin\TActionController;

/**
 * Контроллер действий над настройками
 */
class ActionController extends BaseController implements IConfigIOAware
{
    use TActionController;

    /**
     * Сохраняет форму редактирования конфигурации.
     * @return Response
     */
    protected function actionSave()
    {
        /**
         * @var SettingsComponent $component
         */
        $component = $this->getComponent();

        $config = $this->readConfig($component->getSettingsConfigAlias());
        $form = $this->getForm(self::SETTINGS_FORM_NAME, $config);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        $form->setData($this->getAllPostVars());

        if ($form->isValid()) {
            $this->writeConfig($config);
        } else {
            $this->setResponseStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $form->getView();
    }

}
 