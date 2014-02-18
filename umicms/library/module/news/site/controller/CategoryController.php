<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\news\site\controller;

use umicms\controller\BaseController;
use umicms\module\news\api\CategoryApi;

/**
 * Контроллер отображения категории новостей
 */
class CategoryController extends BaseController
{

    /**
     * @var CategoryApi $categoryApi
     */
    protected $categoryApi;

    public function __construct(CategoryApi $categoryApi)
    {
        $this->categoryApi = $categoryApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $slug = $this->getRouteVar('slug');

        return $this->createViewResponse(
            'category', ['category' => $this->categoryApi->getCategoryBySlug($slug)]
        );
    }
}
 