<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\site;

use umi\hmvc\component\Component;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\http\IHttpAware;
use umi\http\Request;
use umi\http\Response;
use umi\http\THttpAware;
use umicms\module\structure\api\StructureApi;
use umicms\route\SitePageRoute;

/**
 * Приложение сайта.
 */
class SiteApplication extends Component implements IHttpAware
{
    use THttpAware;

    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

    /**
     * {@inheritdoc}
     * @param StructureApi $structureApi
     */
    public function __construct($name, $path, array $options = [], StructureApi $structureApi)
    {
        parent::__construct($name, $path, $options);

        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $isDefaultPage = trim($request->getPathInfo(), '/') === trim($context->getBaseUrl(), '/');

        if (!$isDefaultPage && $response = $this->processDefaultPageRedirect($context)) {
            return $response;
        }

        return null;
    }

    /**
     * Выполняет редирект на базовый url, если пользователь запрашивает станицу по умолчанию
     * по ее прямому url.
     * @param IDispatchContext $context
     * @return Response
     */
    protected function processDefaultPageRedirect(IDispatchContext $context) {
        $routeParams = $context->getRouteParams();

        if (isset($routeParams[SitePageRoute::OPTION_DEFAULT_PAGE])) {
            $currentElement = $this->structureApi->getCurrentElement();
            if ($currentElement->getGUID() === $routeParams[SitePageRoute::OPTION_DEFAULT_PAGE]) {

                $response = $this->createHttpResponse();
                $response->headers->set('Location', $context->getBaseUrl());
                $response->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
                $response->setIsCompleted();

                return $response;
            }
        }

        return null;
    }

}
