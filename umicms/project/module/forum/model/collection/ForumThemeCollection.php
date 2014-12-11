<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\collection\ISimpleHierarchicCollection;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\forum\model\object\ForumAuthor;
use umicms\project\module\forum\model\object\ForumConference;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * Коллекция тем форума.
 *
 * @method CmsSelector|ForumTheme[] select() Возвращает селектор для выбора тем.
 * @method ForumTheme get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тему по GUID
 * @method ForumTheme getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тему по id
 * @method ForumTheme add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает тему
 * @method ForumTheme getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT) Возвращает тему по его последней части ЧПУ
 */
class ForumThemeCollection extends CmsPageCollection implements IRecyclableCollection
{
    use TRecyclableCollection {
        TRecyclableCollection::trash as protected trashInternal;
        TRecyclableCollection::untrash as protected untrashInternal;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'collection:forumTheme';
    }

    public function activate(IActiveAccessibleObject $object)
    {
        if ($object instanceof ForumTheme) {
            if ($object->conference instanceof ForumConference) {
                $object->conference->recalculateThemesCount();
            }
            if ($object->author instanceof ForumAuthor) {
                $object->author->recalculateThemesCount();
            }
        }

        return parent::activate($object);
    }

    public function deactivate(IActiveAccessibleObject $object)
    {
        if ($object instanceof ForumTheme) {
            if ($object->conference instanceof ForumConference) {
                $object->conference->recalculateThemesCount();
            }
            if ($object->author instanceof ForumAuthor) {
                $object->author->recalculateThemesCount();
            }
        }

        return parent::deactivate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(IObject $object)
    {
        if ($object instanceof ForumTheme) {
            if ($object->conference instanceof ForumConference) {
                $object->conference->recalculateThemesCount();
            }
            if ($object->author instanceof ForumAuthor) {
                $object->author->recalculateThemesCount();
            }
        }

        return parent::delete($object);
    }

    /**
     * {@inheritdoc}
     */
    public function trash(IRecyclableObject $object)
    {
        if ($object instanceof ForumTheme) {
            if ($object->conference instanceof ForumConference) {
                $object->conference->recalculateThemesCount();
            }
            if ($object->author instanceof ForumAuthor) {
                $object->author->recalculateThemesCount();
            }
        }

        return $this->trashInternal($object);
    }

    /**
     * {@inheritdoc}
     */
    public function untrash(IRecyclableObject $object)
    {
        if ($object instanceof ForumTheme) {
            if ($object->conference instanceof ForumConference) {
                $object->conference->recalculateThemesCount();
            }
            if ($object->author instanceof ForumAuthor) {
                $object->author->recalculateThemesCount();
            }
        }

        return $this->untrashInternal($object);
    }
}
