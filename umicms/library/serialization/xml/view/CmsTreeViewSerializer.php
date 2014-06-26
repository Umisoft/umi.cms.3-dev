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

use umicms\hmvc\view\CmsTreeView;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор представления дерева.
 */
class CmsTreeViewSerializer extends BaseSerializer
{
    /**
     * Сериализует view в XML.
     * @param CmsTreeView $view
     * @param array $options опции сериализации
     */
    public function __invoke(CmsTreeView $view, array $options = [])
    {
        $variables = iterator_to_array($view, true);
        $options['fields'] = $view->getSelector()->getFields();

        $this->delegate($variables, $options);
    }
}
 