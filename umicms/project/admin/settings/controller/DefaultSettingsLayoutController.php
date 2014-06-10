<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\settings\controller;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\http\Response;
use umicms\exception\RuntimeException;
use umicms\hmvc\controller\BaseCmsController;
use umicms\project\admin\layout\button\behaviour\Behaviour;
use umicms\project\admin\layout\button\Button;
use umicms\project\admin\settings\component\DefaultSettingsComponent;

/**
 * Контроллер чтения и сохранения настроек
 * @todo: требуется рефакторинг, используя Layout
 */
class DefaultSettingsLayoutController extends BaseCmsController implements IConfigIOAware
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

        $valid = true;
        if ($this->isRequestMethodPost()) {

            $formData = $this->getAllPostVars();
            $valid = $form->isValid();

            if ($form->setData($formData) && $valid) {
                $this->writeConfig($config);
            }
        }

        $saveBehaviour = new Behaviour('save');
        $saveButton = new Button($this->getComponent()->translate('button:save'), $saveBehaviour);

        $response = $this->createViewResponse(
            'settings',
            [
                'form' => $form->getView(),
                'toolbar' => [
                    $saveButton->build()
                ]
            ]
        );

        if (!$valid) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
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
 