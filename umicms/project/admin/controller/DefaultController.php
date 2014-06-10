<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\controller;

use umi\http\Response;
use umicms\hmvc\controller\BaseCmsController;
use umicms\project\admin\rest\RestApplication;
use umicms\project\module\users\api\UsersModule;

/**
 * Контроллер интерфейса административной панели.
 */
class DefaultController extends BaseCmsController
{

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
        /**
         * @var RestApplication $restApplication
         */
        $restApplication = $this->getComponent()->getChildComponent('rest');

        $response = $this->createViewResponse('layout', [
            'contents' => $this->response->getContent(),
            'baseUrl' => $this->getUrlManager()->getBaseAdminUrl(),
            'baseApiUrl' => $this->getUrlManager()->getBaseRestUrl(),
            'baseSettingsUrl' => $this->getUrlManager()->getBaseSettingsUrl(),
            'baseSiteUrl' => $this->getUrlManager()->getProjectUrl(),
            'authUrl' => $this->getUrlManager()->getAdminComponentActionResourceUrl($restApplication, 'auth')
        ]);

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }



}