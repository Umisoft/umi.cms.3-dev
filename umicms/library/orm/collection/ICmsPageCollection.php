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
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\collection\behaviour\IRobotsAccessibleCollection;
use umicms\orm\object\ICmsPage;

/**
 * Интерфейс коллекции объектов, которые имеют страницу на сайте.
 */
interface ICmsPageCollection extends ICmsCollection, IRecoverableCollection, IRecyclableCollection, IActiveAccessibleCollection, IRobotsAccessibleCollection
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

    /**
     * Разрешено ли использование slug.
     * @param ICmsObject $object объект, слаг которого необходимо проверить
     * @throws RuntimeException в случае, если коллекция объекта не совпадает с коллекцией, в которой проверяется slug
     * @return bool
     */
    public function isAllowedSlug(ICmsObject $object);
}
 