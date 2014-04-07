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
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\users\api\UsersApi;

/**
 * Контроллер интерфейса административной панели.
 */
class DefaultController extends BaseController implements ILocalesAware, IUrlManagerAware
{

    use TLocalesAware;
    use TUrlManagerAware;

    /**
     * @var Response $response содержимое страницы
     */
    protected $response;

    /**
     * @var UsersApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param Response $response
     * @param UsersApi $api
     */
    public function __construct(Response $response, UsersApi $api)
    {
        $this->response = $response;
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {

        $response = $this->createViewResponse(
            'layout',
            [
                'contents' => $this->response->getContent(),
                'baseUrl' => $this->getUrlManager()->getBaseAdminUrl(),
                'baseApiUrl' => $this->getUrlManager()->getBaseRestUrl(),
                'baseSiteUrl' => $this->getUrlManager()->getProjectUrl(),
                'locale' => $this->getCurrentLocale(),
                'authenticated' => $this->getIsUserAuthenticated()
            ]
        );

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }

    /**
     * Проверяет, залогинен ли пользователь в административную панель
     * @return bool
     */
    protected function getIsUserAuthenticated()
    {
        if ($this->api->isAuthenticated()) {

            $apiComponent = $this->getComponent()->getChildComponent('api');
            if ($this->api->getCurrentUser()->isAllowed($apiComponent, 'administrator', 'controller:settings')) {
                return true;
            }
        }

        return false;
    }

}


