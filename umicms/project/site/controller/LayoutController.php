<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umicms\base\controller\BaseController;
use umicms\project\module\structure\api\StructureApi;

/**
 * Контроллер сетки сайта.
 */
class LayoutController extends BaseController
{

    /**
     * @var Response $response содержимое страницы
     */
    protected $response;
    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

    /**
     * Конструктор.
     * @param Response $response
     * @param StructureApi $structureApi
     */
    public function __construct(Response $response, StructureApi $structureApi)
    {
        $this->response = $response;
        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {

        $currentElement = $this->structureApi->getCurrentElement();
        $layoutName = $this->structureApi->getElementLayout($currentElement)->fileName;

        $response = $this->createViewResponse(
            $layoutName,
            [
                'content' => $this->response->getContent()
            ]
        );

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }

}


