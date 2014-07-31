<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\model;

use umicms\module\BaseModule;
use umicms\project\module\seo\model\collection\RobotsCollection;

/**
 * Модуль для работы со SEO.
 */
class SeoModule extends BaseModule
{

    /**
     * Возвращает коллекцию для работы с robots.
     * @return RobotsCollection
     */
    public function robots()
    {
        return $this->getCollection('robots');
    }
}
