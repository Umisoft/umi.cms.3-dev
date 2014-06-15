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
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;

/**
 * Контроллер действий над настройками
 */
class ActionController extends BaseController implements IConfigIOAware
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $requestMethod = $this->getRequest()->getMethod();

        if (($requestMethod == 'PUT' || $requestMethod == 'POST') && $this->getRouteVar('action') == 'save') {
            return $this->actionSave();
        }

        throw new HttpNotFound('Action not found.');
    }

    /**
     * Сохраняет форму редактирования конфигурации.
     * @return Response
     */
    protected function actionSave()
    {
        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());
        $form = $this->getConfigForm();

        $valid = $form->isValid();

        if ($form->setData($this->getAllPostVars()) && $valid) {
            $this->writeConfig($config);
        }

        $response = $this->createViewResponse(
            'save',
            [
                'save' => $form->getView()
            ]
        );

        if (!$valid) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

}
 