<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install;

use umi\orm\collection\ICollectionManager;

/**
 * Пакет для установки/обновления функционала.
 */
interface IPackage
{
    /**
     * Возвращает конфигурации модулей в пакете.
     * @example ['umicms\project\module\users\model\UsersModule' => '{#lazy:~/project/module/users/configuration/module.config.php}']
     * @return array
     */
    public function getModules();

    /**
     * Возвращает конфигурации административных компонентов.
     * @example ['blog' => '{#lazy:~/project/module/blog/admin/component.config.php}']
     * @return array
     */
    public function getAdminComponents();

    /**
     * Возвращает конфигурации компонентов для сайта.
     * @example ['blog' => '{#lazy:~/project/module/blog/site/component.config.php}']
     * @return array
     */
    public function getSiteComponents();

    /**
     * Возвращает информацию о словарях пакета.
     * @example ['project.admin.rest.structure' => '{#lazy:~/project/module/structure/admin/i18n/dictionary.config.php}'
     * @return array
     */
    public function getI18nDictionaries();

    /**
     * Возвращает информацию о моделях пакета.
     * @example
     * [
     *     'structure' => [
     *        'metadata' => '{#lazy:~/project/module/structure/configuration/structure/metadata.config.php}',
     *        'collection' => '{#lazy:~/project/module/structure/configuration/structure/collection.config.php}',
     *        'scheme' => '{#lazy:~/project/module/structure/configuration/structure/scheme.config.php}'
     *    ]
     * ]
     * @return array
     */
    public function getModels();

    /**
     * Устанавливает/обновляет системные объекты пакета, используя менеджер коллекций.
     * Вызывается инсталлятором после установки всех компонентов пакета.
     * Пакет должен знать о всех GUID собственных системных объектов,
     * при создании объекта GUID должен быть установлен для объекта,
     * при обновлении объекта, он должен быть получен по GUID
     * @param ICollectionManager $collectionManager менеджер коллекций
     */
    public function setPackageData(ICollectionManager $collectionManager);

}
 