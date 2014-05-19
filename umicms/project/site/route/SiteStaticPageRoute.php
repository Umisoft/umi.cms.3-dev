<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\route;

use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\field\special\UriField;
use umi\route\type\BaseRoute;
use umicms\exception\RuntimeException;
use umicms\project\module\structure\api\object\StaticPage;
use umicms\project\site\component\SiteComponent;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\module\structure\api\object\StructureElement;

/**
 * Правила маршрутизации статичных страниц для сайта.
 */
class SiteStaticPageRoute extends BaseRoute implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var StructureModule $systemApi API работы со структурой
     */
    protected $structureApi;

    /**
     * {@inheritdoc}
     * @param StructureModule $structureApi API работы со структурой
     */
    public function __construct(array $options = [], array $subroutes = [], StructureModule $structureApi)
    {
        $this->structureApi = $structureApi;

        parent::__construct($options, $subroutes);
    }

    /**
     * {@inheritdoc}
     */
    public function match($url, $baseUrl = null)
    {
        if ($url === '/') {
            $matched = $this->matchDefaultPage();
        } else {
            $matched = $this->matchPage($url);
        }
        return $matched;
    }

    /**
     * {@inheritdoc}
     */
    public function assemble(array $params = [], array $options = [])
    {
        throw new RuntimeException('Cannot assemble url. Use IUrlManager for url generation.');
    }


    /**
     * Производит маршрутизацию до главной страницы
     * @return bool|int
     */
    protected function matchDefaultPage()
    {
        try {
            $element = $this->structureApi->element()->get($this->getSiteDefaultPageGuid());
            $this->setRouteParams($element);

            return 1;
        } catch(NonexistentEntityException $e) {
            return false;
        }
    }

    /**
     * Производит маршрутизацию до страницы
     * @param string $url
     * @return bool|int
     */
    protected function matchPage($url)
    {
        $element =
            $this->structureApi->element()->select()
            ->types([StaticPage::TYPE])
            ->where(StaticPage::FIELD_URI)
                ->equals(UriField::URI_START_SYMBOL . $url)
            ->limit(1)
            ->result()
            ->fetch();

        if ($element instanceof StructureElement) {
            $this->setRouteParams($element);

            return strlen($element->getURL()) + 1;
        } else {
            return false;
        }
    }

    /**
     * Устанавливает элемент в качестве текущего
     * @param StructureElement $element
     */
    protected function setRouteParams(StructureElement $element)
    {
        $this->params[SiteComponent::MATCH_COMPONENT] = $element->componentName;
        $this->params[SiteComponent::MATCH_STRUCTURE_ELEMENT] = $element;
    }

}