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
use umicms\hmvc\component\BaseCmsController;
use umicms\project\admin\rest\RestApplication;
use umicms\project\module\users\model\UsersModule;

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
     * @var UsersModule $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param Response $response
     * @param UsersModule $module
     */
    public function __construct(Response $response, UsersModule $module)
    {
        $this->response = $response;
        $this->module = $module;
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
            'assetsUrl' => $this->getUrlManager()->getAdminAssetsUrl() . 'development/',
            'projectAssetsUrl' => $this->getUrlManager()->getProjectAssetsUrl(),
            'baseApiUrl' => $this->getUrlManager()->getBaseRestUrl(),
            'baseSiteUrl' => $this->getUrlManager()->getProjectUrl(),
            'authUrl' => $this->getUrlManager()->getAdminComponentActionResourceUrl($restApplication, 'auth'),
            'version' => CMS_VERSION,
            'versionDate' => CMS_VERSION_DATE
        ]);

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }



}