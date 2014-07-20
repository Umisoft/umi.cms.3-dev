<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\recycle\controller;

use umi\hmvc\component\IComponent;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\admin\TActionController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\hmvc\component\admin\collection\ActionController as ActionControllerBase;

/**
 * Контроллер операций c корзиной.
 */
class ActionController extends ActionControllerBase implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * {@inheritdoc}
     */
    protected function getComponent()
    {
        $handlerPath = $this->getCollection()->getHandlerPath('admin');
        $handlerComponent = $this->getContext()->getDispatcher()->getComponentByPath(
            CmsDispatcher::ADMIN_API_COMPONENT_PATH . IComponent::PATH_SEPARATOR . $handlerPath
        );

        return $handlerComponent;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection()
    {
        $collectionName = $this->getQueryVar('collection');

        if (!is_null($collectionName) && !$this->getCollectionManager()->hasCollection($collectionName)) {
            throw new RuntimeException($this->translate(
                'Collection "{collectionName}" is not registered.',
                [
                    'collectionName' => $collectionName
                ]
            ));
        }

        $collection = $this->getCollectionManager()->getCollection($collectionName);

        if (!$collection instanceof IRecyclableCollection) {
            throw new RuntimeException($this->translate(
                'Collection "{collectionName}" is not {collection}',
                [
                    'collectionName' => $collectionName,
                    'collection' => 'IRecyclableCollection'
                ]
            ));
        }

        return $collection;
    }
}
