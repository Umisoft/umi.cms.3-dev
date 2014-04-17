<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\component;

use RuntimeException;
use umi\acl\IAclFactory;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\route\IRouteFactory;
use umicms\hmvc\component\ICollectionComponent;
use umicms\orm\collection\PageCollection;

/**
 * Компонент для вывода простых страниц на сайте.
 */
class DefaultSitePageComponent extends SiteComponent implements ICollectionComponent, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            'index' => 'umicms\project\site\controller\DefaultIndexController',
            'item' => 'umicms\project\site\controller\DefaultItemController'
        ],

        self::OPTION_ACL => [
            IAclFactory::OPTION_ROLES => [
                'viewer' => [],
            ],
            IAclFactory::OPTION_RESOURCES => [
                'controller:index',
                'controller:item'
            ],
            IAclFactory::OPTION_RULES => [
                'viewer' => [
                    'controller:index' => [],
                    'controller:item' => []
                ]
            ]
        ],

        self::OPTION_ROUTES      => [
            'item' => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'route'    => '/{uri}',
                'defaults' => [
                    'controller' => 'item'
                ]
            ],
            'index' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => 'index'
                ]
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        $options = $this->mergeConfigOptions($this->defaultOptions, $options);
        parent::__construct($name, $path, $options);
    }

    /**
     * Возвращает коллекцию, с которой работает компонент.
     * @throws RuntimeException если в конфигурации не указано имя коллекции
     * @return PageCollection
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
        if (!$collection instanceof PageCollection) {
            throw new RuntimeException(
                $this->translate(
                    'Collection "{collection}" for component "{path}" should be instance of PageCollection.',
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
 