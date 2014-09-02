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
use umicms\project\module\seo\model\SeoModule;
use umicms\project\IProjectSettingsAware;
use umicms\project\TProjectSettingsAware;

/**
 * Контроллер robots.txt.
 */
class RobotsController extends BaseCmsController implements IProjectSettingsAware
{
    use TProjectSettingsAware;

    /**
     * * @var SeoModule $collection модуль "Seo"
     */
    private $module;

    /**
     * Конструкторю
     * @param SeoModule $module модуль "Структура"
     */
    public function __construct(SeoModule $module)
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
                'host' => $this->getProjectSettings()->get('defaultDomain')
            ]
        )->setIsCompleted();
        $response->headers->set('Content-type', 'text/plain');

        return $response;
    }
}