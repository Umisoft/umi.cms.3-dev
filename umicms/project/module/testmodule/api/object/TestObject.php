<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\testmodule\api\object;

use umicms\orm\object\CmsObject;

/**
 * Test.
 */
class TestObject extends CmsObject
{
    const TEXT = 'text';
    const TEXTAREA = 'textarea';
    const SELECT = 'select';
    const RADIO = 'radio';
    const PASSWORD = 'password';
    const MULTISELECT = 'multiselect';
    const CHECKBOX_GROUP = 'checkbox_group';
    const CHECKBOX = 'checkbox';

    const DATE = 'date';
    const DATE_TIME = 'date_time';
    const EMAIL = 'email';
    const NUMBER = 'number';
    const TIME = 'time';
    const FILE = 'file';
    const IMAGE = 'image';

    /**
     * Возвращает значение в виде массива
     * @return array
     */
    public function getMultiSelectValue()
    {
        if ($value = $this->getProperty(self::MULTISELECT)->getValue()) {
            return unserialize($value);
        }

        return [];
    }

    /**
     * Устанавливает значение в виде строки
     * @param array $list
     * @return $this
     */
    public function setMultiSelectValue(array $list)
    {
        $this->getProperty(self::MULTISELECT)->setValue(serialize($list));

        return $this;
    }

    /**
     * Возвращает значение в виде массива
     * @return array
     */
    public function getCheckboxGroupValue()
    {
        if ($value = $this->getProperty(self::CHECKBOX_GROUP)->getValue()) {
            return unserialize($value);
        }

        return [];
    }

    /**
     * Устанавливает значение в виде строки
     * @param array $list
     * @return $this
     */
    public function setCheckboxGroupValue(array $list)
    {
        $this->getProperty(self::CHECKBOX_GROUP)->setValue(serialize($list));

        return $this;
    }
}
