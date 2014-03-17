<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umicms\orm\collection\ICmsCollection;

/**
 * Трейт для поддержки вывода форм
 * @mixin BaseRestActionController
 */
trait TCollectionFormAction
{
    /**
     * Возвращает коллекцию.
     * @param string $collectionName имя коллекции
     * @return ICmsCollection
     */
    abstract protected function getCollection($collectionName);

    /**
     * Возвращает значение параметра из GET-параметров запроса.
     * @param string $name имя параметра
     * @throws HttpException если значение не найдено
     * @return mixed
     */
    abstract protected function getRequiredQueryVar($name);

    /**
     * Возвращает форму для объектного типа коллекции.
     * @return IForm
     */
    protected function actionForm()
    {
        $collectionName = $this->getRequiredQueryVar('collection');
        $typeName = $this->getRequiredQueryVar('type');
        $formName = $this->getRequiredQueryVar('form');

        return $this->getCollection($collectionName)->getForm($typeName, $formName);
    }
}
 