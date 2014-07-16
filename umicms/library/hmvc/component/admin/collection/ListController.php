<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\collection;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;
use umi\orm\metadata\IObjectType;
use umicms\exception\RuntimeException;
use umicms\orm\collection\CmsCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;
use umicms\orm\selector\TSelectorConfigurator;

/**
 * Контроллер действий над списком.
 */
class ListController extends BaseController
{
    use TSelectorConfigurator;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {

                $list = $this->applySelectorConditions($this->getList());
                $result = ['collection' => $list];

                if ($list->getLimit()) {
                    $result['meta'] = [
                        'limit' => $list->getLimit(),
                        'offset' => $list->getOffset(),
                        'total' => $list->getTotal()
                    ];
                }

                return $this->createViewResponse(
                    'list',
                    $result
                );
            }
            case 'PUT':
            case 'POST': {
                $object = $this->create($this->getCollectionIncomingData());

                return $this->createViewResponse(
                    'item', [$this->getCollectionName() => $object]
                );
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    'HTTP method is not implemented.',
                    ['GET', 'POST', 'PUT']
                );
            }

            default: {
                throw new HttpException(
                    Response::HTTP_NOT_IMPLEMENTED,
                    'HTTP method is not implemented.'
                );
            }
        }
    }

    /**
     * Возвращает список объектов коллекции, с которой работает компонент.
     * @return CmsSelector
     */
    protected function getList() 
    {
        return  $this->getComponent()->getCollection()->select();
    }

    /**
     * Создает и возвращает объект списка.
     * @param array $data данные
     * @throws RuntimeException если невозможно создать объект
     * @return ICmsObject
     */
    protected function create(array $data)
    {
        /**
         * @var CmsHierarchicCollection|CmsCollection $collection
         */
        $collection = $this->getCollection();

        switch(true) {
            case $collection instanceof CmsHierarchicCollection: {
                $object = $this->createHierarchicObject($collection, $data);
                break;
            }
            case $collection instanceof CmsCollection: {
                $object = $this->createObject($collection, $data);
                break;
            }

            default: {
                throw new RuntimeException(
                    $this->translate(
                        'Cannot create object in collection "{collection}". Unknown collection type.',
                        ['collection' => $this->getCollectionName()]
                    )
                );
            }
        }

        return $this->save($object, $data);
    }

    /**
     * Применяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {

        $selector->limit((int) $this->getQueryVar('limit'), (int) $this->getQueryVar('offset'));

        if ($fields = $this->getQueryVar('fields')) {
            $this->applySelectorSelectedFields($selector, $fields);
        }

        if (is_array($this->getQueryVar('with'))) {
            $this->applySelectorWith($selector, $this->getQueryVar('with'));
        }

        if (is_array($this->getQueryVar('orderBy'))) {
            $this->applySelectorOrderBy($selector, $this->getQueryVar('orderBy'));
        }

        if (is_array($this->getQueryVar('filters'))) {
            $this->applySelectorConditionFilters($selector, $this->getQueryVar('filters'));
        }

        return $selector;
    }

    /**
     * Создает объект в коллекции.
     * @param CmsHierarchicCollection $collection коллекция
     * @param array $data данные объекта
     * @throws RuntimeException в случае недостаточности данных для создания объекта
     * @return CmsHierarchicObject
     */
    private function createHierarchicObject(CmsHierarchicCollection $collection, array $data)
    {
        if (!isset($data['slug'])) {
            throw new RuntimeException(
                $this->translate('Cannot create hierarchic object. Option "{option}" required.',
                    ['option' => 'slug']
                )
            );
        }

        $typeName = isset($data['type']) ? $data['type'] : IObjectType::BASE;
        $parent = null;
        if (isset($data['parent'])) {
            /**
             * @var CmsHierarchicObject $parent
             */
            $parent = $collection->getById($data['parent']);
        }

        return $collection->add($data['slug'], $typeName, $parent);

    }

    /**
     * Создает объект в коллекции.
     * @param CmsCollection $collection коллекция
     * @param array $data данные объекта
     * @return ICmsObject
     */
    private function createObject(CmsCollection $collection, array $data)
    {
        $typeName = isset($data['type']) ? $data['type'] : IObjectType::BASE;

        return $collection->add($typeName);
    }

}
