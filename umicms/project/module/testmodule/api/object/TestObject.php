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
}
