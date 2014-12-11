<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\model;

use umi\rss\IRssFeedAware;
use umi\rss\TRssFeedAware;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\forum\model\collection\ForumConferenceCollection;
use umicms\project\module\forum\model\collection\ForumThemeCollection;
use umicms\project\module\forum\model\object\ForumConference;
use umicms\project\module\forum\model\object\ForumTheme;

/**
 * API модуля "Форум".
 */
class ForumModule extends BaseModule implements IRssFeedAware, IUrlManagerAware
{
    use TRssFeedAware;
    use TUrlManagerAware;

    /**
     * Возвращает коллекцию конференций.
     * @return ForumConferenceCollection
     */
    public function conference()
    {
        return $this->getCollection('forumConference');
    }

    /**
     * Возвращает коллекцию тем.
     * @return ForumThemeCollection
     */
    public function theme()
    {
        return $this->getCollection('forumTheme');
    }

    /**
     * Возвращает список конференций.
     * @return CmsSelector|ForumConference[]
     */
    public function getConference()
    {
        return $this->conference()->select();
    }

    /**
     * Возвращает список тем.
     * @return CmsSelector|ForumTheme[]
     */
    public function getTheme(ForumConference $conference)
    {
        return $this->theme()->select()
            ->where(ForumTheme::FIELD_CONFERENCE)
                ->equals($conference);
    }
}
 