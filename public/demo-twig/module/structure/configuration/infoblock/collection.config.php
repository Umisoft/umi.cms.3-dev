<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\Slider;
use umicms\project\module\structure\model\collection\InfoBlockCollection;

return [
    'forms' => [
        Slider::TYPE => [
            InfoBlockCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/infoblock/form/slider.edit.config.php}',
            InfoBlockCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/infoblock/form/slider.create.config.php}'
        ]
    ]
];