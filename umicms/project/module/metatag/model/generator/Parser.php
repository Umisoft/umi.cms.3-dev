<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\metatag\model\generator;

/**
 * Class Parser
 * @package umicms\project\module\metatag\model\generator
 */
class Parser
{
    /**
     * @param  string $template
     * @return array
     */
    public function parse($template)
    {
        $template = trim($template);
        $result = [];
        if (strlen($template) > 0){
            $result = preg_split("/({[^}]*})/", $template, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            foreach ($result as $key => $value) {
                if ($value[0] == '{') {
                    $result[$key] = explode('|', str_replace('}', '', str_replace('{', '', $value)));
                }
            }
        }
        return $result;
    }

    /**
     * @param array $template
     * @return string
     */
    public function randomize(array $template)
    {
        return implode('', array_map(function($template) {
            if (is_array($template)) {
                return $template[array_rand($template)];
            } else {
                return $template;
            }
        }, $template));
    }

    /**
     * @param string $template
     * @return array
     */
    public function parseTokens($template)
    {
        $matched = [];
        preg_match_all("/(%[^%]*%)/", $template, $matched);
        return array_keys(array_flip($matched[0]));
    }
} 