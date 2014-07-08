<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\field\special\UriField;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TRecoverableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;

/**
 * Коллекция иерархических объектов, которые имеют страницу на сайте.
 */
class CmsHierarchicPageCollection extends CmsHierarchicCollection implements ICmsPageCollection
{
    use TRecoverableCollection;
    use TRecyclableCollection;
    use TActiveAccessibleCollection;

    /**
     * {@inheritdoc}
     */
    public function getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT)
    {
        $uri = '/' . ltrim($uri, '/');

        $object =  $this->select()
            ->where(CmsHierarchicObject::FIELD_URI)
            ->equals(UriField::URI_START_SYMBOL . $uri)
            ->limit(1)
            ->localization($localization)
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

    /**
     * {@inheritdoc}
     */
    public function getIndexablePropertyNames()
    {
        return [
            ICmsPage::FIELD_DISPLAY_NAME,
            ICmsPage::FIELD_PAGE_H1,
            ICmsPage::FIELD_PAGE_META_TITLE,
            ICmsPage::FIELD_PAGE_CONTENTS
        ];
    }

}
 