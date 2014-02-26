<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site\controller;

use umicms\base\controller\BaseController;
use umicms\exception\RuntimeException;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\StaticPage;

/**
 * Контроллер для вывода простой страницы.
 */
class StaticPageController extends BaseController
{
    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

    public function __construct(StructureApi $structureApi) {
        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $currentElement = $this->structureApi->getCurrentElement();

        if (!$currentElement instanceof StaticPage) {
            throw new RuntimeException($this->translate(
                'Current structure element is not static page.'
            ));
        }

        return $this->createViewResponse(
            'page',
            [
                'page' => $currentElement
            ]
        );
    }
}
