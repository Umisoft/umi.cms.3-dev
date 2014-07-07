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
use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\layout\control\TableControl;
use umicms\hmvc\component\admin\TActionController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\collection\behaviour\IRecyclableCollection;

/**
 * Контроллер операций c корзиной.
 */
class ActionController extends BaseController implements ICollectionManagerAware
{
    use TActionController;
    use TCollectionManagerAware;

    /**
     * Возвращает удалённые страницы коллекции.
     * @throws RuntimeException в случае если коллекция не существует или она не IRecyclableCollection
     * @return TableControl
     */
    protected function actionGetTableControl()
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


        $handlerPath = $collection->getHandlerPath('admin');
        $handlerComponent = $this->getContext()->getDispatcher()->getComponentByPath(
            CmsDispatcher::ADMIN_API_COMPONENT_PATH . IComponent::PATH_SEPARATOR . $handlerPath
        );

        return new TableControl($handlerComponent);
    }
}
