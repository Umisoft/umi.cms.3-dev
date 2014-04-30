<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umi\i18n\ILocalesService;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\object\ICmsPage;

/**
 * Интерфейс коллекции объектов, которые имеют страницу на сайте.
 */
interface ICmsPageCollection extends ICmsCollection, IRecoverableCollection, IRecyclableCollection, IActiveAccessibleCollection
{
    /**
     * Возвращает объект по URI.
     * @param string $uri URI
     * @param string $localization указание на локаль, в которой загружается объект.
     * По умолчанию объект загружается в текущей локали. Можно указать другую конкретную локаль
     * @throws NonexistentEntityException если не удалось получить объект
     * @return ICmsPage
     */
    public function getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT);

}
 