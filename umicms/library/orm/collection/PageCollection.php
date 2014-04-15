<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TRecoverableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\object\ICmsPage;

/**
 * Коллекция объектов, которые имеют страницу на сайте.
 */
class PageCollection extends SimpleCollection
    implements IRecoverableCollection, IRecyclableCollection, IActiveAccessibleCollection
{
    use TRecoverableCollection;
    use TRecyclableCollection;
    use TActiveAccessibleCollection;

    /**
     * Возвращает объект по последней части ЧПУ.
     * @param string $slug
     * @param bool $withLocalization загружать ли значения локализованных свойств объекта.
     * @throws NonexistentEntityException если объект не существует
     * @return ICmsPage
     */
    public function getBySlug($slug, $withLocalization = false)
    {
        $selector = $this->select()
            ->withLocalization($withLocalization)
            ->where(ICmsPage::FIELD_PAGE_SLUG)
            ->equals($slug);

        $page = $selector->getResult()->fetch();

        if (!$page instanceof ICmsPage) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $slug, 'collection' => $this->getName()]
            ));
        }

        return $page;
    }
}
 