<?php
namespace umicms\route;

use umi\hmvc\component\IComponent;
use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\orm\exception\NonexistentEntityException;
use umi\orm\object\IHierarchicObject;
use umi\orm\selector\condition\IFieldConditionGroup;
use umi\orm\selector\ISelector;
use umi\route\type\BaseRoute;
use umicms\module\structure\api\StructureApi;
use umicms\module\structure\model\StructureElement;

/**
 * Правила роутинга для страниц сайта.
 */
class SitePageRoute extends BaseRoute implements IHttpAware
{
    use THttpAware;

    /**
     * Опция для установки страницы по умолчанию
     */
    const OPTION_DEFAULT_PAGE = 'page';

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
            return $this->matchDefaultPage();
        }

        return $this->matchPage($url);
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
        if (!isset($this->defaults[self::OPTION_DEFAULT_PAGE])) {
            return false;
        }

        try {
            $element = $this->structureApi->getElement($this->defaults[self::OPTION_DEFAULT_PAGE]);
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
        $selector = $this->structureApi->select();
        $urlParts = explode('/', $url);

        $selector->begin(IFieldConditionGroup::MODE_OR);
        for ($i = 0; $i < count($urlParts); $i++) {
            $part = array_slice($urlParts, 0, $i + 1);
            $partUrl = '//' . implode('/', $part); // TODO UriField::URI_START_SYMBOL
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

            return strlen($element->getURL());
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
        $this->params[IComponent::MATCH_COMPONENT] = $element->module;
    }

}