<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml\view;

use umicms\hmvc\view\CmsTreeNode;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор ноды дерева.
 */
class CmsTreeNodeSerializer extends BaseSerializer
{
    /**
     * Сериализует ноду дерева в XML.
     * @param CmsTreeNode $node
     * @param array $options опции сериализации
     */
    public function __invoke(CmsTreeNode $node, array $options = [])
    {
        $this->delegate($node->item, $options);
        $this->delegate(iterator_to_array($node, true), $options);
        $this->delegate(['current' => $node->current, 'active' => $node->active]);
    }
}
 