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
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TRecoverableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\object\ICmsPage;

/**
 * Коллекция объектов, которые имеют страницу на сайте.
 */
class PageCollection extends SimpleCollection implements ICmsPageCollection
{
    use TRecoverableCollection;
    use TRecyclableCollection;
    use TActiveAccessibleCollection;

    /**
     * {@inheritdoc}
     */
    public function getByUri($uri, $withLocalization = false)
    {
        $selector = $this->select()
            ->withLocalization($withLocalization)
            ->where(ICmsPage::FIELD_PAGE_SLUG)
            ->equals($uri);

        $page = $selector->getResult()->fetch();

        if (!$page instanceof ICmsPage) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $uri, 'collection' => $this->getName()]
            ));
        }

        return $page;
    }
}
 