<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json\view;

use umicms\hmvc\view\CmsView;
use umicms\serialization\json\BaseSerializer;

/**
 * Сериализатор View.
 */
class CmsViewSerializer extends BaseSerializer
{
    /**
     * Сериализует View.
     * @param CmsView $view
     * @param array $options опции сериализации
     */
    public function __invoke(CmsView $view, array $options = [])
    {
        $this->configure($view);

        $result = [];

        foreach ($view as $name => $value) {
            if (in_array($name, $this->currentExcludes)) {
                continue;
            }
            $result[$name] = $value;
        }

        $this->delegate($result, $options);
    }
}
 