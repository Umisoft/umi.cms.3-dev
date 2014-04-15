<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TRecoverableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;

/**
 * Коллекция иерархических объектов, которые имеют страницу на сайте.
 */
class PageHierarchicCollection extends SimpleHierarchicCollection
    implements IRecoverableCollection, IRecyclableCollection, IActiveAccessibleCollection
{
    use TRecoverableCollection;
    use TRecyclableCollection;
    use TActiveAccessibleCollection;

}
 