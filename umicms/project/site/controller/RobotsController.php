<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\controller;

use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\structure\model\StructureModule;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер robots.txt.
 */
class RobotsController extends BaseCmsController implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * * @var StructureModule $collection модуль "Структура"
     */
    private $module;

    /**
     * Конструкторю
     * @param StructureModule $module модуль "Структура"
     */
    public function __construct(StructureModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $response = $this->createViewResponse(
            'module/structure/robots/robots',
            [
                'pages' => $this->module->robots()->select()->result(),
                'host' => $this->getSiteSettings()->get('defaultDomain')
            ]
        )->setIsCompleted();
        $response->headers->set('Content-type', 'text/plain');

        return $response;
    }
}