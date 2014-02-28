<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\route;

use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\field\special\UriField;
use umi\orm\object\IHierarchicObject;
use umi\route\type\BaseRoute;
use umicms\hmvc\component\BaseComponent;
use umicms\project\config\ISiteSettingsAware;
use umicms\project\config\TSiteSettingsAware;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\StructureElement;

/**
 * Правила роутинга для сайта.
 */
class SiteStaticPageRoute extends BaseRoute implements ISiteSettingsAware
{
    use TSiteSettingsAware;

    /**
     * @var StructureApi $systemApi API работы со структурой
     */
    protected $structureApi;

    /**
     * {@inheritdoc}
     * @param StructureApi $structureApi API работы со структурой
     */
    public function __construct(array $options = [], array $subroutes = [], StructureApi $structureApi)
    {
        $this->structureApi = $structureApi;

        parent::__construct($options, $subroutes);
    }

    /**
     * {@inheritdoc}
     */
    public function match($url)
    {
        if (is_null($url)) {
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
        return ''; // TODO
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

            return 0;
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
            ->types(['static'])
            ->where(IHierarchicObject::FIELD_URI)
                ->equals(UriField::URI_START_SYMBOL . '/' . $url)
            ->limit(1)
            ->result()
            ->fetch();

        if ($element instanceof StructureElement) {
            $this->setRouteParams($element);

            return strlen($element->getURL()) - 1;
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
        $this->params[BaseComponent::MATCH_COMPONENT] = $element->componentPath;
        $this->params[BaseComponent::MATCH_ELEMENT] = $element;
    }

}