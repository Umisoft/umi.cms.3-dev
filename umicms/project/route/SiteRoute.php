<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\route;

use umi\hmvc\component\IComponent;
use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\orm\exception\NonexistentEntityException;
use umi\orm\metadata\field\special\UriField;
use umi\orm\object\IHierarchicObject;
use umi\orm\selector\condition\IFieldConditionGroup;
use umi\orm\selector\ISelector;
use umi\route\type\BaseRoute;
use umicms\project\config\ISiteSettingsAware;
use umicms\project\config\TSiteSettingsAware;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\StructureElement;

/**
 * Правила роутинга для сайта.
 */
class SiteRoute extends BaseRoute implements IHttpAware, ISiteSettingsAware
{
    use THttpAware;
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

        $this->params['isMatchingComplete'] = (strlen($url) === $matched);

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
            $this->setCurrentElement($element);

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
        $selector = $this->structureApi->element()->select();
        $urlParts = explode('/', $url);

        $selector->begin(IFieldConditionGroup::MODE_OR);
        for ($i = 0; $i < count($urlParts); $i++) {
            $part = array_slice($urlParts, 0, $i + 1);
            $partUrl = UriField::URI_START_SYMBOL . '/' . implode('/', $part);
            $selector
                ->where(IHierarchicObject::FIELD_URI)
                ->equals($partUrl);
        }
        $selector->end();
        $selector->orderBy(IHierarchicObject::FIELD_HIERARCHY_LEVEL, ISelector::ORDER_DESC);
        $selector->limit(1);

        $element = $selector
            ->result()
            ->fetch();

        if ($element instanceof StructureElement) {
            $this->setCurrentElement($element);

            return strlen($element->getURL()) - 1;
        } else {
            return false;
        }
    }

    /**
     * Устанавливает элемент в качестве текущего
     * @param StructureElement $element
     */
    protected function setCurrentElement(StructureElement $element)
    {
        $this->structureApi->setCurrentElement($element);
        $this->params[IComponent::MATCH_COMPONENT] = $element->componentPath;
    }

}