<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\object;

use umi\i18n\ILocalesService;
use umicms\Utils;

/**
 * Посетитель.
 *
 * @property string $ip IP
 * @property string $token авторизационный токен
 */
class Visitor extends Guest
{
    /**
     * Имя типа.
     */
    const TYPE_NAME = 'visitor';

    /**
     * Имя поля для хранения ip
     */
    const FIELD_IP = 'ip';
    /**
     * Имя поля для хранения авторизационного токена
     */
    const FIELD_TOKEN = 'token';

    /**
     * Обновляет авторизационный токен посетителя.
     * @return $this
     */
    public function updateToken()
    {
        $this->getProperty(self::FIELD_TOKEN)->setValue(Utils::generateGUID());

        return $this;
    }

    /**
     * Меняет тип.
     * @param string $typeName новое имя типа
     */
    public function changeType($typeName)
    {
        if ($typeName === $this->getTypeName()) {
            return;
        }

        $type = $this->getCollection()->getMetadata()->getType($typeName);

        $this->fullyLoad(ILocalesService::LOCALE_ALL);

        $initialValues = $this->getInitialValues();
        if (isset($initialValues[self::FIELD_TYPE])) {
            unset($initialValues[self::FIELD_TYPE]);
        }

        $this->properties = [];
        $this->initialValues = [];

        $this->type = $type;
        $this->typeName = $typeName;
        $this->setInitialValues($initialValues);

        $this->getProperty(self::FIELD_TYPE)->setValue($this->getTypePath());

    }
}
 