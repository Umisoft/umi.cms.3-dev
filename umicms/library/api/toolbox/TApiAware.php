<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\toolbox;

use umicms\api\IPublicApi;
use umicms\exception\RequiredDependencyException;

/**
 * Трейт для получения API
 */
trait TApiAware
{
    /**
     * @var ApiTools $traitApiTools
     */
    private $traitApiTools;

    /**
     * @see IApiAware::setApiTools()
     */
    public function setApiTools(ApiTools $apiTools)
    {
        $this->traitApiTools = $apiTools;
    }

    /**
     * Возвращает API по имени класса.
     * @param string $apiClassName
     * @return IPublicApi
     */
    protected function getApi($apiClassName)
    {
        return $this->getApiTools()->getService('umicms\api\IPublicApi', $apiClassName);
    }

    /**
     * Возвращает инструментарий для работы с API.
     * @throws RequiredDependencyException если инструментарий не был внедрен
     * @return ApiTools
     */
    private function getApiTools()
    {
        if (!$this->traitApiTools) {
            throw new RequiredDependencyException(sprintf(
                'API Tools are not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitApiTools;
    }
}
 