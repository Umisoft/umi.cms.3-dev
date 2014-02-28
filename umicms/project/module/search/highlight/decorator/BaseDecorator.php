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
 * Class BaseDecorator
 */
abstract class BaseDecorator
{
    /**
     * @param Fragment $fragment
     * @return mixed
     */
    abstract public function decorateFragment(Fragment $fragment);

    /**
     * @param $keyword
     * @return mixed
     */
    abstract public function highlightResult($keyword);
}
