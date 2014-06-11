<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest\controller;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\form\IForm;
use umicms\exception\RuntimeException;
use umicms\project\admin\controller\base\BaseAdminController;
use umicms\project\admin\rest\component\SettingsComponent;

/**
 * Базовый класс контроллера компонента, управляющего настройками.
 */
abstract class BaseSettingsComponentController extends BaseAdminController implements IConfigIOAware
{
    use TConfigIOAware;

    /**
     * Имя формы редактирования конфигурации
     */
    const SETTINGS_FORM_NAME = 'settings';

    /**
     * Возвращает форму, связанную с конфигурацией
     * @return IForm
     */
    protected function getConfigForm()
    {
        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());

        $form = $this->getForm(self::SETTINGS_FORM_NAME, $config);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        return $form;
    }

    /**
     * Возвращает компонент, у которого вызван контроллер.
     * @throws RuntimeException при неверном классе компонента
     * @return SettingsComponent
     */
    protected function getComponent()
    {
        $component = parent::getComponent();

        if (!$component instanceof SettingsComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Component for controller "{controllerClass}" should be instance of "{componentClass}".',
                    [
                        'controllerClass' => get_class($this),
                        'componentClass' => 'umicms\project\admin\rest\component\SettingsComponent'
                    ]
                )
            );
        }

        return $component;
    }
}
 