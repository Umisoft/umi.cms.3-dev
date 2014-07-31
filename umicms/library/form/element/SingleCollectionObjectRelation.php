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
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;

/**
 * Элемент формы для работы с объектами из произвольных коллекций
 */
class SingleCollectionObjectRelation extends BaseFormInput implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'singleCollectionObjectRelation';

    /**
     * {@inheritdoc}
     */
    protected $type = 'singleCollectionObjectRelation';
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

        if (!isset($this->options['collection'])) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot build view for SingleCollectionObjectRelation form element. Option "collection" required.'
                )
            );
        }

        $collectionName = $this->options['collection'];
        /**
         * @var ICmsCollection $collection
         */
        $collection = $this->getCollectionManager()->getCollection($collectionName);
        $view->collection = [
            'id' => $collectionName,
            'displayName' => $collection->translate('collection:' . $collectionName . ':displayName')
        ];
    }
}
 