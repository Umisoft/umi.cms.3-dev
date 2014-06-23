<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site\author\view\controller;

use umicms\hmvc\component\site\BaseSitePageController;
use umicms\project\module\blog\model\BlogModule;

class IndexController extends BaseSitePageController
{
    /**
     * @var BlogModule $module модуль "Блоги"
     */
    private $module;

    /**
     * Конструктор.
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'index',
            [
                'page' => $this->getCurrentPage(),
                'author' => $this->module->getCurrentAuthor()
            ]
        );
    }
}
 