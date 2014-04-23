<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\controller;

use umi\http\Response;
use umi\i18n\ILocalesAware;
use umi\i18n\TLocalesAware;
use umicms\hmvc\controller\BaseController;
use umicms\project\module\users\api\UsersModule;

/**
 * Контроллер интерфейса административной панели.
 */
class DefaultController extends BaseController implements ILocalesAware
{
    use TLocalesAware;

    /**
     * @var Response $response содержимое страницы
     */
    protected $response;

    /**
     * @var UsersModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param Response $response
     * @param UsersModule $api
     */
    public function __construct(Response $response, UsersModule $api)
    {
        $this->response = $response;
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $result = [
            'contents' => $this->response->getContent(),
            'baseUrl' => $this->getUrlManager()->getBaseAdminUrl(),
            'baseApiUrl' => $this->getUrlManager()->getBaseRestUrl(),
            'baseSiteUrl' => $this->getUrlManager()->getProjectUrl(),
            'locale' => $this->getCurrentLocale(),
            'authenticated' => $this->isApiAllowed()
        ];

        if ($this->isSettingsAllowed()) {
            $result['baseSettingsUrl'] = $this->getUrlManager()->getBaseSettingsUrl();
        }

        $response = $this->createViewResponse(
            'layout', $result
        );

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }

    /**
     * Проверяет, имеет ли пользователь доступ к API
     * @return bool
     */
    protected function isApiAllowed()
    {
        if ($this->api->isAuthenticated()) {

            $application = $this->getComponent()->getChildComponent('api');
            if ($this->api->getCurrentUser()->isAllowed($application, 'controller:settings')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверяет, имеет ли пользователь доступ к настройкам
     * @return bool
     */
    protected function isSettingsAllowed()
    {
        if ($this->api->isAuthenticated()) {

            $application = $this->getComponent()->getChildComponent('settings');
            if ($this->api->getCurrentUser()->isAllowed($application, 'controller:settings')) {
                return true;
            }
        }

        return false;
    }

}


