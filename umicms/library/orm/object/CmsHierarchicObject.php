<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use umi\orm\object\HierarchicObject;
use umi\orm\objectset\IObjectSet;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\selector\CmsSelector;

/**
 * Класс иерархического объекта UMI.CMS.

 * @property string $slug последняя часть ЧПУ
 * @property int $level уровень вложенности в иерархии
 * @property int $order порядок следования в иерархии
 * @property CmsHierarchicObject|null $parent родительский элемент
 * @property IObjectSet $children дочерние элементы
 */
class CmsHierarchicObject extends HierarchicObject implements ICmsObject, IUrlManagerAware
{
    use TCmsObject;

    /**
     *  Имя поля для хранения дочерних элементов.
     */
    const FIELD_CHILDREN = 'children';

    /**
     * @var string $normalizedURL нормализованный url объекта
     */
    private $normalizedUrl;

    /**
     * {@inheritdoc}
     */
    public function getURL()
    {
        if (is_null($this->normalizedUrl)) {
            $url = $this->getProperty(self::FIELD_URI)
                ->getValue();
            $this->normalizedUrl = substr($url, 2);
        }

        return $this->normalizedUrl;
    }

    /**
     * Возвращает селектор для выбора родителей страницы.
     * @throws RuntimeException в случае, если коллекция не иерархическая
     * @return CmsSelector
     */
    public function getAncestry()
    {
        $collection = $this->getCollection();

        if (!$collection instanceof CmsHierarchicCollection) {
            throw new RuntimeException(sprintf(
                'Cannot get ancestry. Collection "%s" is not hierarchic.',
                $collection->getName()
            ));
        }

        return $collection->selectAncestry($this);
    }
}
