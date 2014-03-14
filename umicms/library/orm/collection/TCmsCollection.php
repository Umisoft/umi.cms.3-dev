<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umicms\exception\OutOfBoundsException;
use umicms\orm\selector\CmsSelector;

/**
 * Трейт коллекции объектов UMI.CMS
 * @mixin ICmsCollection
 */
trait TCmsCollection
{

    /**
     * @var callable $selectorInitializer инициализатор для селектора
     */
    protected static $selectorInitializer;

    /**
     * Устанавливает инициализатор для селектора
     * @param callable $initializer
     */
    public static function setSelectorInitializer(callable $initializer = null)
    {
        self::$selectorInitializer = $initializer;
    }

    /**
     * Возвращает новый селектор для формирования выборки объектов коллекции.
     * @return CmsSelector
     */
    public function select()
    {
        /**
         * @var CmsSelector $selector
         */
        /** @noinspection PhpUndefinedMethodInspection */
        /** @noinspection PhpUndefinedClassInspection */
        $selector = parent::select();

        if ($initializer = self::$selectorInitializer) {
            $initializer($selector);
        }

        return $selector;
    }


    /**
     * @see ICmsCollection::getHandlerPath()
     */
    public function getHandlerPath($applicationName)
    {
        if (!$this->hasHandler($applicationName)) {
            throw new OutOfBoundsException(
                sprintf(
                    'Handler for collection "%s" and application "%s" is unknown.',
                    $this->getName(),
                    $applicationName
                )
            );
        }

        return $this->traitGetConfig()['handlers'][$applicationName];
    }

    /**
     * @see ICmsCollection::getHandlerList()
     */
    public function getHandlerList()
    {
        return (isset($this->traitGetConfig()['handlers'])) ? $this->traitGetConfig()['handlers'] : [];
    }

    /**
     * @see ICmsCollection::hasHandler()
     */
    public function hasHandler($applicationName)
    {
        return isset($this->traitGetConfig()['handlers'][$applicationName]);
    }

    /**
     * Возвращает конфигурацию коллекции.
     * @return array
     */
    private function traitGetConfig()
    {
       return isset($this->config) ? $this->config : [];
    }
}
 