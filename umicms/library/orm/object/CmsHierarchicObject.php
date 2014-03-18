<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\object;

use umi\orm\object\HierarchicObject;
use umi\orm\objectset\IObjectSet;

/**
 * Класс иерархического объекта UMI.CMS.

 * @property string $slug последняя часть ЧПУ
 * @property int $level уровень вложенности в иерархии
 * @property int $order порядок следования в иерархии
 * @property CmsHierarchicObject|null $parent родительский элемент
 * @property int $childCount количество дочерних элементов
 * @property IObjectSet $children дочерние элементы
 */
class CmsHierarchicObject extends HierarchicObject implements ICmsObject
{
    /**
     *  Имя поля для хранения дочерних элементов
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
}
