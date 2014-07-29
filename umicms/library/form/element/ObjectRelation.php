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

use umi\form\element\BaseFormInput;
use umi\form\FormEntityView;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\orm\collection\ICmsCollection;

/**
 * Элемент формы для работы с объектами из произвольных коллекций
 */
class ObjectRelation extends BaseFormInput implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'objectRelation';

    /**
     * {@inheritdoc}
     */
    protected $type = 'objectRelation';
    /**
     * {@inheritdoc}
     */
    protected $inputType = 'text';

    /**
     * {@inheritdoc}
     */
    protected function extendView(FormEntityView $view)
    {
        parent::extendView($view);
        $view->attributes['type'] = $this->inputType;

        $collectionNames = isset($this->options['collections']) ? $this->options['collections'] :$this->getAllowedCollections();
        $collections = [];

        foreach ($collectionNames as $collectionName) {

            /**
             * @var ICmsCollection $collection
             */
            $collection = $this->getCollectionManager()->getCollection($collectionName);

            $collections[] = [
               'id' => $collectionName,
               'displayName' => $collection->translate('collection:' . $collectionName . ':displayName')
            ];
        }

        $view->collections = $collections;
    }

    /**
     * Возвращает список разрешенных коллекций.
     * @return array
     */
    protected function getAllowedCollections()
    {
        return $this->getCollectionManager()->getList();
    }
}
 