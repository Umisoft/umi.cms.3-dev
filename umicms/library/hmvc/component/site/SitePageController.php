<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\site;

use umi\http\Response;
use umicms\exception\RuntimeException;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;

/**
 * Контроллер вывода страниц компонента.
 */
class SitePageController extends BaseSitePageController
{
    /**
     * @var string $template имя шаблона, по которому выводится результат
     */
    public $template = 'page';

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
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     *
     * @templateParam umicms\orm\object\ICmsPage $page текущая страница
     *
     * @return Response
     */
    public function __invoke()
    {
        $uri = $this->getRouteVar('uri');
        $page = $this->getPage($uri);

        if ($page instanceof CmsHierarchicObject) {
            foreach ($page->getAncestry() as $parent) {
                $this->pushCurrentPage($parent);
            }
        }

        $this->pushCurrentPage($page);

        return $this->createViewResponse(
            $this->template,
            [
                'page' => $page
            ]
        );
    }

    /**
     * Возвращает компонент, у которого вызван контроллер.
     * @throws RuntimeException при неверном классе компонента
     * @return BaseSitePageComponent
     */
    protected function getComponent()
    {
        $component = parent::getComponent();

        if (!$component instanceof BaseSitePageComponent) {
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
 