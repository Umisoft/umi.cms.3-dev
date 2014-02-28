<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\highlight\decorator;

use umicms\project\module\search\highlight\Fragment;

/**
 * Class HtmlDecorator
 */
class HtmlDecorator extends BaseDecorator
{

    /**
     * @param Fragment $fragment
     * @return mixed
     */
    public function decorateFragment(Fragment $fragment)
    {
        return '...' . $fragment->toString() . '...';
    }

    /**
     * @param $keyword
     * @return mixed
     */
    public function highlightResult($keyword)
    {
        return '<span>'.$keyword.'</span>';
    }
}
