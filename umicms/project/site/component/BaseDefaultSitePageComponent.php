<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\component;

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
abstract class BaseDefaultSitePageComponent extends SiteComponent implements ICollectionComponent, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);
        parent::__construct($name, $path, $options);
    }

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
 