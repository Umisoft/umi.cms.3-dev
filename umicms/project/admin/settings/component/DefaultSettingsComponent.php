<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\settings\component;

use umi\route\IRouteFactory;
use umicms\exception\RuntimeException;

/**
 * Компонент настроек.
 */
class DefaultSettingsComponent extends SettingsComponent
{

    /**
     * Опция для задания алиаса пути к настройкам, управляемым компонентом
     */
    const OPTION_SETTINGS_CONFIG_ALIAS = 'settingsConfigAlias';

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            'index' => 'umicms\project\admin\settings\controller\DefaultSettingsController'
        ],

        self::OPTION_ROUTES => [
            'index' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'priority'  => 200,
                'defaults' => [
                    'controller' => 'index'
                ]
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);
        parent::__construct($name, $path, $options);
    }

    /**
     * Возвращает алиас пути к настройкам, управляемым компонентом
     * @throws RuntimeException
     * @return string
     */
    public function getSettingsConfigAlias()
    {
        if (!isset($this->options[self::OPTION_SETTINGS_CONFIG_ALIAS])) {
            throw new RuntimeException(
                $this->translate(
                    'Option "{option}" is required for component "{path}".',
                    [
                        'option' => self::OPTION_SETTINGS_CONFIG_ALIAS,
                        'path' => $this->getPath()
                    ]
                )
            );
        }

        return $this->options[self::OPTION_SETTINGS_CONFIG_ALIAS];
    }

}
 