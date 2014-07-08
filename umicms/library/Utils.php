<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms;

/**
 * Библиотека функций.
 */
class Utils
{
    /**
     * Генерирует GUID
     * @return string
     */
    public static function generateGUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Проверяет, имеет ли GUID правильный формат
     * @param string $guid
     * @return bool
     */
    public static function checkGUIDFormat($guid)
    {
        return strlen($guid) && preg_match('#^\S{8}-\S{4}-\S{4}-\S{4}-\S{12}$#', $guid);
    }

    /**
     * Парсит строку адресов в массив, в котром значениями являются email, если имя для email не задано,
     * или имя и email в качестве ключа.
     * @param string $emailListString (пример строки: User <user@domail.com>, another.user@domail.com)
     * @return array
     */
    public static function parseEmailList($emailListString)
    {
        $emailList = [];
        if (preg_match_all('/([^,<>\s]+)\s*(<(.*)>)?,*\s*/', $emailListString, $matches)) {
            foreach($matches[1] as $key => $value) {
                if (!empty($matches[3][$key])) {
                    $key = $matches[3][$key];
                }
                $emailList[$key]  = $value;
            }
        }

        return $emailList ? $emailList : null;
    }
}