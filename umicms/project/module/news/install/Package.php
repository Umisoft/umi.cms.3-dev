<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\install;

use umi\orm\collection\ICollectionManager;
use umicms\install\IPackage;

/**
 * Возвращает информацию о ресурсах пакета для установщика
 */
class Package implements IPackage
{
    /**
     * {@inheritdoc}
     */
    public function getModules()
    {
        return [
            'umicms\project\module\news\model\NewsModule' => '{#lazy:~/project/module/news/configuration/module.config.php}'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponents()
    {
        return [
            'news' => '{#lazy:~/project/module/news/admin/component.config.php}'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteComponents()
    {
        return [
            'site' => '{#lazy:~/project/module/news/site/component.config.php}'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getI18nDictionaries()
    {
        return [
            'project.admin.rest.news' => '{#lazy:~/project/module/news/admin/i18n/dictionary.config.php}',
            'project.admin.rest.news.item' => '{#lazy:~/project/module/news/admin/item/i18n/dictionary.config.php}',
            'project.admin.rest.news.rubric' => '{#lazy:~/project/module/news/admin/rubric/i18n/dictionary.config.php}',
            'project.admin.rest.news.subject' => '{#lazy:~/project/module/news/admin/subject/i18n/dictionary.config.php}',
            'project.admin.rest.news.rss' => '{#lazy:~/project/module/news/admin/rss/i18n/dictionary.config.php}'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getModels()
    {
        return [
            'newsRubric' => [
                'collection' => '{#lazy:~/project/module/news/configuration/rubric/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/rubric/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/rubric/scheme.config.php}'
             ],
            'newsItem' => [
                'collection' => '{#lazy:~/project/module/news/configuration/item/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/item/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/item/scheme.config.php}'
             ],
            'newsRssImportScenario' => [
                'collection' => '{#lazy:~/project/module/news/configuration/rss/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/rss/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/rss/scheme.config.php}'
             ],
            'newsItemSubject' => [
                'collection' => '{#lazy:~/project/module/news/configuration/itemsubject/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/itemsubject/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/itemsubject/scheme.config.php}'
             ],
            'rssScenarioSubject' => [
                'collection' => '{#lazy:~/project/module/news/configuration/rsssubject/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/rsssubject/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/rsssubject/scheme.config.php}'
             ],
            'newsSubject' => [
                'collection' => '{#lazy:~/project/module/news/configuration/subject/collection.config.php}',
                'metadata' => '{#lazy:~/project/module/news/configuration/subject/metadata.config.php}',
                'scheme' => '{#lazy:~/project/module/news/configuration/subject/scheme.config.php}',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setPackageData(ICollectionManager $collectionManager)
    {

    }
}
 