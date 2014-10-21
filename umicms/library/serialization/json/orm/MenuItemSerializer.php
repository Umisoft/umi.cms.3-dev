<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json\orm;

use umicms\orm\object\ICmsObject;
use umicms\project\module\structure\model\object\IMenuItem;

/**
 * Json-сериализатор пункта произвольного меню.
 */
class MenuItemSerializer extends CmsObjectSerializer
{
    /**
     * {@inheritdoc}
     */
    protected function buildProperties(ICmsObject $object, array &$properties)
    {
        if ($object instanceof IMenuItem) {
            if (isset($properties['meta'])) {
                $properties['meta'] = [];
            }
            $properties['meta']['url'] = $object->getItemUrl();
        }
    }
}
 