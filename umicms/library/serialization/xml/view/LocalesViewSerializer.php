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

use umicms\hmvc\view\LocalesView;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для представления локалей.
 */
class LocalesViewSerializer extends BaseSerializer
{
    /**
     * Сериализует представление локалей в XML.
     * @param LocalesView $view
     * @param array $options опции сериализации
     */
    public function __invoke(LocalesView $view, array $options = [])
    {
        foreach ($view as $localeInfo) {
            $this->getXmlWriter()->startElement('locale');
            $this->writeAttribute('id', $localeInfo['id']);
            if (isset($localeInfo['url'])) {
                $this->getXmlWriter()->writeAttribute('url', $localeInfo['url']);
            }
            if (isset($localeInfo['current'])) {
                $this->getXmlWriter()->writeAttribute('current', (int) $localeInfo['current']);
            }
            $this->getXmlWriter()->endElement();
        }

    }
}
