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
use umicms\exception\RuntimeException;
use umicms\hmvc\controller\BaseController;
use umicms\project\admin\settings\component\DefaultSettingsComponent;

/**
 * Контроллер чтения и сохранения настроек
 */
class DefaultSettingsController extends BaseController implements IConfigIOAware
{
    use TConfigIOAware;

    /**
     * Имя формы редактирования конфигурации
     */
    const SETTINGS_FORM_NAME = 'settings';

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());

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

    /**
     * Возвращает компонент, у которого вызван контроллер.
     * @throws RuntimeException при неверном классе компонента
     * @return DefaultSettingsComponent
     */
    protected function getComponent()
    {
        $component = parent::getComponent();

        if (!$component instanceof DefaultSettingsComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Component for controller "{controllerClass}" should be instance of "{componentClass}".',
                    [
                        'controllerClass' => get_class($this),
                        'componentClass' => 'umicms\project\admin\settings\component\DefaultSettingsComponent'
                    ]
                )
            );
        }

        return $component;
    }
}
 