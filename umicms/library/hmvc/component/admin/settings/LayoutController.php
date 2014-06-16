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

        return $this->createViewResponse(
            'editForm',
            [
                'submitToolbar' => [
                    $saveButton->build()
                ],
                'form' => $this->getConfigForm()->getView()
            ]
        );
    }
}
 