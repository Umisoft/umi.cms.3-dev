<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\route;

use umi\orm\object\IHierarchicObject;
use umi\route\type\BaseRoute;
use umicms\project\site\component\SiteComponent;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\module\structure\object\SystemPage;

/**
 * Правила роутинга для сайта.
 */
class SiteComponentRoute extends BaseRoute
{
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
        $slugs = explode('/', $url);
        if (count($slugs) < 2) {
            return false;
        }
        list(, $slug) = $slugs;

        //TODO учитывать предыдущий выбранный элемент
        $element =
            $this->structureApi->element()->select()
            ->types(['system'])
            ->where(IHierarchicObject::FIELD_SLUG)
                ->equals($slug)
            ->limit(1)
            ->result()
            ->fetch();

        if ($element instanceof SystemPage) {

            $this->params[SiteComponent::MATCH_COMPONENT] = $element->componentPath;
            $this->params[SiteComponent::MATCH_STRUCTURE_ELEMENT] = $element;

            return strlen($slug) + 1;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assemble(array $params = [], array $options = [])
    {
        return ''; // TODO
    }

}