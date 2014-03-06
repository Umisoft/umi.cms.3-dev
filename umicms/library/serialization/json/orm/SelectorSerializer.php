<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\orm;

use umi\orm\selector\Selector;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для селектора.
 */
class SelectorSerializer extends BaseSerializer
{
    /**
     * Сериализует Selector в JSON.
     * @param Selector $selector
     * @param array $options опции сериализации
     */
    public function __invoke(Selector $selector, array $options = [])
    {
        $fields = $selector->getFields();

        $this->delegate($selector->getResult()->fetchAll(), ['fields' => $fields]);
    }

}
 