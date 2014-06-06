<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component;

use umi\acl\IAclFactory;
use umi\acl\IAclManager;
use umi\acl\IAclResource;
use umi\config\entity\IConfig;
use umi\hmvc\component\Component;
use umicms\exception\UnexpectedValueException;

/**
 * {@inheritdoc}
 */
abstract class BaseCmsComponent extends Component implements IAclResource
{
    /**
     * Имя опции для задания настроек.
     */
    const OPTION_SETTINGS = 'settings';
    /**
     * Префикс имени ACL-ресурса
     */
    const ACL_RESOURCE_PREFIX = 'component:';
    /**
     * Константа для формирования имен ролей для дочерних компонентов
     */
    const EXECUTOR_ROLE_SUFFIX = 'Executor';
    /**
     * @var IAclManager $aclManager менеджер ACL
     */
    private $aclManager;

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return self::ACL_RESOURCE_PREFIX . $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclManager()
    {
        if (!$this->aclManager) {

            $config = isset($this->options[self::OPTION_ACL]) ? $this->options[self::OPTION_ACL] : [];
            $config = $this->configToArray($config, true);
            $config = $this->mergeConfigOptions($config, $this->getChildComponentsAcl());

            $this->aclManager = $this->getAclFactory()->createAclManager($config);
        }

        return $this->aclManager;
    }

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
     * Возвращает значение настройки для компонента.
     * @param string $settingName имя настройки
     * @param mixed $defaultValue значение по умолчанию
     * @return mixed
     */
    public function getSetting($settingName, $defaultValue = null)
    {
        if (isset($this->getSettings()[$settingName])) {
            return $this->getSettings()[$settingName];
        }

        return $defaultValue;
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

    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        return [
            'name'        => $this->getName(),
            'displayName' => $this->translate('component:' . $this->getName() . ':displayName')
        ];
    }

    /**
     * Возвращает список имен словарей в которых будет производиться поиск перевода сообщений и лейблов
     * данного компонента. Приоритет поиска соответсвует последовательности словарей в списке.
     * @return array
     */
    public function getDictionariesNames()
    {
        return $this->getI18nDictionaryNames();
    }

    /**
     * Возвращает конфигурацию ACL для дочерних компонентов
     * @return array
     */
    protected function getChildComponentsAcl()
    {
        $roleNames = $this->getChildComponentNames();
        array_walk($roleNames, function($name) {
            $name = $name . self::EXECUTOR_ROLE_SUFFIX;
        });

        $resources = $this->getChildComponentNames();
        array_walk($roleNames, function($name) {
            $name = self::ACL_RESOURCE_PREFIX . $name;
        });

        $rules = [];
        foreach ($resources as $key => $resourceName) {
            $rules[$resourceName] = [
                $roleNames[$key] => []
            ];
        }

        $config = [
            IAclFactory::OPTION_ROLES => array_fill_keys($roleNames, []),
            IAclFactory::OPTION_RESOURCES => $resources,
            IAclFactory::OPTION_RULES => $rules
        ];


        return $config;
    }

}
