<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\captcha\toolbox;

/**
 * Конфигурация для регистрации набора инструментов.
 */

return [
    'name'     => CaptchaTools::NAME,
    'class'    => __NAMESPACE__ . '\CaptchaTools',
    'awareInterfaces' => [
        'umicms\captcha\ICaptchaAware'
    ]
];
