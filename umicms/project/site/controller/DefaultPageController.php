<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umicms\exception\RuntimeException;
use umicms\orm\object\ICmsPage;
use umicms\project\site\component\BaseDefaultSitePageComponent;

/**
 * Контроллер вывода страниц компонента.
 */
class DefaultPageController extends SitePageController
{
    /**
     * Возвращает страницу для отображения.
     * @param string $uri
     * @return ICmsPage
     */
    protected function getPage($uri)
    {
        return $this->getComponent()->getCollection()->getByUri($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $uri = $this->getRouteVar('uri');
        $page = $this->getPage($uri);

        $this->pushCurrentPage($page);

        return $this->createViewResponse(
            'page',
            [
                'page' => $page
            ]
        );
    }

    /**
     * Возвращает компонент, у которого вызван контроллер.
     * @throws RuntimeException при неверном классе компонента
     * @return BaseDefaultSitePageComponent
     */
    protected function getComponent()
    {
        $component = parent::getComponent();

        if (!$component instanceof BaseDefaultSitePageComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Component for controller "{controllerClass}" should be instance of "{componentClass}".',
                    [
                        'controllerClass' => get_class($this),
                        'componentClass' => 'umicms\hmvc\component\ICollectionComponent'
                    ]
                )
            );
        }

        return $component;
    }
}
 