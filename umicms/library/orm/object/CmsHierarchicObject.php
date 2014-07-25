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
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;

/**
 * Класс иерархического объекта UMI.CMS.

 * @property string $slug последняя часть ЧПУ
 * @property int $level уровень вложенности в иерархии
 * @property int $order порядок следования в иерархии
 * @property CmsHierarchicObject|null $parent родительский элемент
 * @property IObjectSet $children дочерние элементы
 * @property int $siteChildCount количество дочерних элементов, отображаемых на сайте
 * @property int $adminChildCount количество дочерних элементов, отображаемых в административной панели

 */
class CmsHierarchicObject extends HierarchicObject implements ICmsObject, IUrlManagerAware
{
    use TCmsObject;

    /**
     *  Имя поля для хранения дочерних элементов.
     */
    const FIELD_CHILDREN = 'children';
    /**
     * Имя поля, используемого для хранения количества непосредственных детей, отображаемых на сайте
     */
    const FIELD_SITE_CHILD_COUNT = 'siteChildCount';
    /**
     * Имя поля, используемого для хранения количества непосредственных детей, отображаемых в административной панели
     */
    const FIELD_ADMIN_CHILD_COUNT = 'childCount';

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

    /**
     * Вычисляет количество дочерних элементов отображаемых на сайте
     * @param string|null $localeId
     * @return int
     */
    public function calculateSiteChildCount($localeId = null)
    {
        /**
         * @var ICmsCollection $collection
         */
        $collection = $this->getCollection();

        $select = $collection->getInternalSelector()
            ->where(self::FIELD_PARENT)
                ->equals($this);

        if ($collection instanceof IActiveAccessibleCollection) {
            $select->where(IActiveAccessibleObject::FIELD_ACTIVE, $localeId)->equals(true);
        }
        if ($collection instanceof IRecyclableCollection) {
            $select->where(IRecyclableObject::FIELD_TRASHED)->equals(false);
        }

        return $select->getTotal();
    }

    /**
     * Вычисляет количество дочерних элементов отображаемых в административной панели
     * @return int
     */
    public function calculateAdminChildCount()
    {
        /**
         * @var ICmsCollection $collection
         */
        $collection = $this->getCollection();

        $select = $collection->getInternalSelector()
            ->where(self::FIELD_PARENT)
            ->equals($this);

        if ($collection instanceof IRecyclableCollection) {
            $select->where(IRecyclableObject::FIELD_TRASHED)->equals(false);
        }

        return $select->getTotal();
    }
}
