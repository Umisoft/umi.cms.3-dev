<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
    const CHECKBOX = 'checkbox';

    const DATE = 'date';
    const DATE_TIME = 'date_time';
    const EMAIL = 'email';
    const NUMBER = 'number';
    const TIME = 'time';
    const FILE = 'file';
    const IMAGE = 'image';
}
