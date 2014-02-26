<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\base\component;

use umi\config\entity\IConfig;
use umi\hmvc\component\Component as FrameworkComponent;
use umicms\exception\UnexpectedValueException;

/**
 * {@inheritdoc}
 */
class Component extends FrameworkComponent
{
    /**
     * Имя опции для задания настроек.
     */
    const OPTION_SETTINGS = 'settings';

    /**
     * Возвращает настройки компонента.
     * @throws UnexpectedValueException
     * @return IConfig
     */
    public function getSettings()
    {
        $settings = isset($this->options[self::OPTION_SETTINGS]) ? $this->options[self::OPTION_SETTINGS] : null;

        if (!$settings instanceof IConfig) {
            throw new UnexpectedValueException($this->translate(
                'Component "{path}" settings should be instance of IConfig.',
                ['path' => $this->getPath()]
            ));
        }

        return $settings;
    }

    /**
     * Возвращает список имен дочерних компонентов.
     * @return array
     */
    public function getChildComponentNames()
    {
        if (isset($this->options[self::OPTION_COMPONENTS])) {
            return array_keys($this->configToArray($this->options[self::OPTION_COMPONENTS]));
        }

        return [];
    }

}
