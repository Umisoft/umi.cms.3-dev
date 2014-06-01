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
use umicms\hmvc\url\IUrlManagerAware;
use umicms\orm\selector\CmsSelector;

/**
 * Класс иерархического объекта UMI.CMS.

 * @property string $slug последняя часть ЧПУ
 * @property int $level уровень вложенности в иерархии
 * @property int $order порядок следования в иерархии
 * @property CmsHierarchicObject|null $parent родительский элемент
 * @property int $childCount количество дочерних элементов
 * @property IObjectSet $children дочерние элементы
 */
class CmsHierarchicObject extends HierarchicObject implements ICmsObject, IUrlManagerAware
{
    /**
     *  Имя поля для хранения дочерних элементов
     */
    const FIELD_CHILDREN = 'children';

    use TCmsObject;

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
     * @return CmsSelector
     */
    public function getAncestry()
    {
        return $this->getCollection()->selectAncestry($this);
    }
}
