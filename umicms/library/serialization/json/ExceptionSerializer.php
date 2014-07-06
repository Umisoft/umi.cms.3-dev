<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use umicms\project\Environment;

/**
 * JSON-сериализатор для исключений.
 */
class ExceptionSerializer extends BaseSerializer
{
    /**
     * Сериализует исключение в JSON.
     * @param \Exception $exception
     * @param array $options опции сериализации
     */
    public function __invoke(\Exception $exception, array $options = [])
    {
        $info = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];

        if (Environment::$showExceptionTrace) {
            $info['trace'] = $exception->getTraceAsString();
        }

        $this->delegate($info, $options);
    }
}
