<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace project\module\structure\model\object;

use umicms\project\module\structure\model\object\BaseInfoBlock;

/**
 * Слайдер.
 */
class Slider extends BaseInfoBlock
{
    const TYPE = 'slider';

    /**
     * Имя поля для заголовка слайда 1
     */
    const FIELD_SLIDE_NAME_1 = 'slideName1';
    /**
     * Имя поля для заголовка слайда 2
     */
    const FIELD_SLIDE_NAME_2 = 'slideName2';
    /**
     * Имя поля для заголовка слайда 3
     */
    const FIELD_SLIDE_NAME_3 = 'slideName3';

    /**
     * Имя поля для контента слайда 1
     */
    const FIELD_SLIDE_CONTENT_1 = 'slideContent1';
    /**
     * Имя поля для контента слайда 2
     */
    const FIELD_SLIDE_CONTENT_2 = 'slideContent2';
    /**
     * Имя поля для контента слайда 3
     */
    const FIELD_SLIDE_CONTENT_3 = 'slideContent3';
}
 