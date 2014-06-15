<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\route;

use umi\orm\metadata\field\special\UriField;
use umi\route\type\BaseRoute;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\site\component\SiteComponent;
use umicms\project\module\structure\model\StructureModule;
use umicms\project\module\structure\model\object\SystemPage;

/**
 * Правила маршрутизации компонентов для сайта.
 */
class SiteComponentRoute extends BaseRoute implements IUrlManagerAware
{

    use TUrlManagerAware;

    /**
     * @var StructureModule $systemApi API работы со структурой
     */
    protected $structureApi;

    /**
     * @var StructureElement[] $cache
     */
    private static $cache = [];

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
        $slugs = explode('/', $url);
        if (count($slugs) < 2) {
            return false;
        }
        list(, $slug) = $slugs;

        $projectUrl = $this->getUrlManager()->getProjectUrl();
        if ($projectUrl != '/' && strpos($baseUrl, $projectUrl) === 0) {
            $baseUrl = (string) substr($baseUrl, strlen($projectUrl));
        }

        $pageUri = UriField::URI_START_SYMBOL . $baseUrl . '/' . $slug;

        if (!isset(self::$cache[$pageUri])) {
            self::$cache[$pageUri] = $this->structureApi->element()->selectSystem()
                ->where(SystemPage::FIELD_URI)
                ->equals($pageUri)
                ->limit(1)
                ->result()->fetch();
        }

        if (self::$cache[$pageUri] instanceof SystemPage) {

            $this->params[SiteComponent::MATCH_COMPONENT] = self::$cache[$pageUri]->componentName;
            $this->params[SiteComponent::MATCH_STRUCTURE_ELEMENT] = self::$cache[$pageUri];

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
        throw new RuntimeException('Cannot assemble url. Use IUrlManager for url generation.');
    }

}