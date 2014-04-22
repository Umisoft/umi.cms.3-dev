<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\settings\controller;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umicms\hmvc\controller\BaseController;

/**
 * Контроллер чтения и сохранения настроек
 */
class SettingsController extends BaseController implements IConfigIOAware
{
    use TConfigIOAware;

    /**
     * Имя формы редактирования конфигурации
     */
    const SETTINGS_FORM_NAME = 'settings';

    protected $configPath = '~/project/module/service/configuration/backup/collection.settings.config.php';

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $config = $this->readConfig($this->configPath);
        $form = $this->getForm(self::SETTINGS_FORM_NAME, $config);
        $form->setAction($this->getUrl('index'));

        if ($this->isRequestMethodPost()) {

            $formData = $this->getAllPostVars();

            if ($form->setData($formData) && $form->isValid()) {
                $this->writeConfig($config);

                return $this->createRedirectResponse(
                    $this->getRequest()->getReferer()
                );
            }

        }

        return $this->createViewResponse(
            'settings',
            [
                'form' => $form
            ]
        );
    }
}
 