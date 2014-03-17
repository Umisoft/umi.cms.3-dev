<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\site\controller;

use umicms\project\site\controller\SitePageController;
use umicms\project\module\structure\api\StructureApi;

/**
 * Контроллер для вывода простой страницы.
 */
class StaticPageController extends SitePageController
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
        return $this->createViewResponse(
            'page',
            [
                'page' => $this->getCurrentPage()
            ]
        );
    }
}
