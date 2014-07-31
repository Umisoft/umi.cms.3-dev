<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\form\element;

use umicms\orm\collection\ICmsPageCollection;

/**
 * Элемент формы для работы со страницами из произвольных коллекций
 */
class PageRelation extends ObjectRelation
{

    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'pageRelation';

    /**
     * {@inheritdoc}
     */
    protected $type = 'pageRelation';

    /**
     * {@inheritdoc}
     */
    protected function getAllowedCollections()
    {
        $collections = [];
        foreach($this->getCollectionManager()->getList() as $collectionName) {
            $collection = $this->getCollectionManager()->getCollection($collectionName);
            if ($collection instanceof ICmsPageCollection) {
                $collections[] = $collectionName;
            }
        }

        return $collections;
    }
}
 