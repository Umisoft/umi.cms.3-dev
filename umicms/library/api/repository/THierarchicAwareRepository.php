<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\repository;

use umi\orm\metadata\field\special\MaterializedPathField;
use umicms\exception\InvalidArgumentException;
use umicms\orm\collection\CommonHierarchy;
use umicms\orm\collection\LinkedHierarchicCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт для подключения функцилнала иерархических объектов в репозиторий.
 */
trait THierarchicAwareRepository
{
    /**
     * Возвращает коллекцию.
     * @internal
     * @return SimpleCollection|SimpleHierarchicCollection|CommonHierarchy|LinkedHierarchicCollection
     */
    abstract public function getCollection();

    /**
     * Возвращает селектор для выбора объектов из репозитория.
     * @return CmsSelector
     */
    abstract protected function select();

    /**
     * Возвращает сообщение из указанного словаря, переведенное для текущей или указанной локали.
     * Текст сообщения может содержать плейсхолдеры. Ex: File "{path}" not found
     * Если идентификатор локали не указан, будет использована текущая локаль.
     * @param string $message текст сообщения на языке разработки
     * @param array $placeholders значения плейсхолдеров для сообщения. Ex: array('{path}' => '/path/to/file')
     * @param string $localeId идентификатор локали в которую осуществляется перевод (ru, en_us)
     * @return string
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * Возвращает селектор для выбора дочерних объектов для указанного.
     * @param CmsHierarchicObject|null $object объект, либо null, если нужна выборка от корня
     * @return CmsSelector
     */
    public function selectChildren(CmsHierarchicObject $object = null)
    {
        return $this->select()
            ->where(CmsHierarchicObject::FIELD_PARENT)->equals($object)
            ->orderBy(CmsHierarchicObject::FIELD_ORDER);
    }

    /**
     * Возвращает селектор для выбора потомков указанного объекта, либо от корня.
     * @param CmsHierarchicObject|null $object объект, либо null, если нужна выборка от корня
     * @param int|null $depth глубина выбора потомков, по умолчанию выбираются на всю глубину
     * @throws InvalidArgumentException если глубина указана не верно
     * @return CmsSelector
     */
    public function selectDescendants(CmsHierarchicObject $object = null, $depth = null)
    {
        if (!is_null($depth) && !is_int($depth) && $depth < 0) {
            throw new InvalidArgumentException($this->translate(
                'Cannot select descendants. Invalid argument "depth" value.'
            ));
        }

        if ($depth == 1) {
            return $this->selectChildren($object);
        }

        $selector = $this->select();

        if ($object) {
            $selector
                ->where(CmsHierarchicObject::FIELD_MPATH)
                ->like($object->getMaterializedPath() . MaterializedPathField::MPATH_SEPARATOR . '%');
        }

        if ($depth) {
            $selector
                ->where(CmsHierarchicObject::FIELD_HIERARCHY_LEVEL)
                ->equalsOrLess($object->getLevel() + $depth);
        }

        $selector->orderBy(CmsHierarchicObject::FIELD_ORDER);

        return $selector;
    }


    /**
     * Возвращает селектор для выбора родителей объекта.
     * @param CmsHierarchicObject $object
     * @return CmsSelector
     */
    public function selectAncestry(CmsHierarchicObject $object)
    {
        return $this->getCollection()
            ->selectAncestry($object);
    }

    /**
     * Возвращает объект по его Uri
     * @param string $uri Uri объекта
     * @return CmsHierarchicObject
     */
    protected function selectByUri($uri)
    {
        return $this->getCollection()->getByUri('/' . ltrim($uri, '/'));
    }

}
