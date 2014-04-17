<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umi\orm\metadata\field\special\UriField;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TRecoverableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Коллекция иерархических объектов, которые имеют страницу на сайте.
 */
class PageHierarchicCollection extends SimpleHierarchicCollection implements ICmsPageCollection
{
    use TRecoverableCollection;
    use TRecyclableCollection;
    use TActiveAccessibleCollection;

    /**
     * {@inheritdoc}
     */
    public function getByUri($uri, $withLocalization = false)
    {
        $uri = '/' . ltrim($uri, '/');

        $object =  $this->select()
            ->where(CmsHierarchicObject::FIELD_URI)
            ->equals(UriField::URI_START_SYMBOL . $uri)
            ->limit(1)
            ->withLocalization($withLocalization)
            ->result()
            ->fetch();

        if (!$object instanceof CmsHierarchicObject) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Object with URI "{uri}" does not exist in collection "{collection}".',
                    ['uri' => $uri, 'collection' => $this->getName()]
                )
            );
        }

        return $object;
    }

}
 