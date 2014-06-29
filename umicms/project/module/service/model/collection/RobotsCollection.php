<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\CmsCollection;
use umicms\orm\object\behaviour\IRobotsAccessibleObject;
use umicms\project\module\service\model\object\Robots;

/**
 * Коллекция страниц входящих в robots.txt.
 *
 * @method Robots get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о странице по ее GUID
 * @method Robots getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает запись о странице по ее id
 * @method Robots add($typeName = IObjectType::BASE) Добавляет запись о странице и возвращает её
 */
class RobotsCollection extends CmsCollection
{
    /**
     * Добавляет страницу в robots
     * @param IRobotsAccessibleObject $page добавляемая страница
     * @return Robots
     */
    public function disallow(IRobotsAccessibleObject $page)
    {
        return $this->add()
            ->setValue(Robots::FIELD_DISPLAY_NAME, $page->displayName)
            ->setValue(Robots::FIELD_PAGE_RELATION, $page);
    }

    /**
     * Удаляет страницу из robots
     * @param IRobotsAccessibleObject $page удаляемая страница
     * @return Robots
     */
    public function allow(IRobotsAccessibleObject $page)
    {
        $robots = $this->select()
            ->where(Robots::FIELD_PAGE_RELATION)->equals($page)
            ->result()
            ->fetch();

        if ($robots instanceof IRobotsAccessibleObject) {
            $this->delete($robots);
        }

        return $this;
    }

    /**
     * Проверяет наличие страницы в robots
     * @param IRobotsAccessibleObject $page проверяемая страница
     * @return bool
     */
    public function checkPage(IRobotsAccessibleObject $page)
    {
        $robots = $this->select()
            ->where(Robots::FIELD_PAGE_RELATION)->equals($page)
            ->limit(1)
            ->result()
            ->count();

        return (bool) $robots < 0;
    }
}
