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

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\ICollectionComponent;
use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;

/**
 * Базовый компонент для вывода страниц на сайте.
 */
abstract class BaseSitePageComponent extends SiteComponent implements ICollectionComponent, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Возвращает URI страницы относительно компонента.
     * @param ICmsPage $page страница
     * @throws RuntimeException если невозможно получить URI
     * @return string
     */
    public function getPageUri(ICmsPage $page)
    {
        if (!$this->getCollection()->contains($page)) {
            throw new RuntimeException(
                $this->translate(
                    'Component "{path}" does not support URI generation for page "{guid}" from collection "{collection}".',
                    [
                        'path' => $this->getPath(),
                        'guid' => $page->getGUID(),
                        'collection' => $page->getCollection()->getName()
                    ]
                )
            );
        }

        $uri = ($page instanceof CmsHierarchicObject) ? $page->getURL() : $page->slug;

        return $this->getRouter()->assemble('page', ['uri' => $uri]);
    }

    /**
     * Возвращает коллекцию, с которой работает компонент.
     * @throws RuntimeException если в конфигурации не указано имя коллекции
     * @return ICmsPageCollection
     */
    public function getCollection()
    {
        if (!isset($this->options[self::OPTION_COLLECTION_NAME])) {
            throw new RuntimeException(
                $this->translate(
                    'Option "{option}" is required for component "{path}".',
                    [
                        'option' => self::OPTION_COLLECTION_NAME,
                        'path' => $this->getPath()
                    ]
                )
            );
        }

        $collection = $this->getCollectionManager()->getCollection($this->options[self::OPTION_COLLECTION_NAME]);
        if (!$collection instanceof ICmsPageCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Collection "{collection}" for component "{path}" should be instance of ICmsPageCollection.',
                    [
                        'collection' => self::OPTION_COLLECTION_NAME,
                        'path' => $this->getPath()
                    ]
                )
            );
        }

        return $collection;
    }
}
 