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

use umi\form\IForm;
use umicms\hmvc\component\admin\layout\button\behaviour\Behaviour;
use umicms\hmvc\component\admin\layout\button\Button;

/**
 * Контроллер вывода настроек компонента, управляющего настройками
 */
class LayoutController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $saveBehaviour = new Behaviour('save');
        $saveButton = new Button($this->getComponent()->translate('button:save'), $saveBehaviour);
        $saveButton->attributes['hasIcon'] = false;
        $saveButton->attributes['class'] = 'button';

        return $this->createViewResponse(
            'simpleForm',
            [
                'simpleForm' => [
                    'i18n' => [
                        'Nothing is selected' => $this->getComponent()->translate('Nothing is selected')
                    ],
                    'submitToolbar' => [
                        $saveButton->build()
                    ],
                    'meta' => $this->getConfigForm()->getView()
                ]
            ]
        );
    }

    /**
     * Возвращает форму для редактирования конфига
     * @return IForm
     */
    protected function getConfigForm()
    {
        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());

        $form = $this->getForm(self::SETTINGS_FORM_NAME, $config);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        return $form;
    }
}
 