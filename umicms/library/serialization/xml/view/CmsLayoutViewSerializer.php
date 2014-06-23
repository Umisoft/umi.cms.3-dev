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

use umicms\hmvc\view\CmsLayoutView;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для сетки.
 */
class CmsLayoutViewSerializer extends BaseSerializer
{
    /**
     * Сериализует сетку в XML.
     * @param CmsLayoutView $layout
     * @param array $options опции сериализации
     */
    public function __invoke(CmsLayoutView $layout, array $options = [])
    {
        foreach ($layout as $name => $value) {

            switch($name) {
                case 'locales':
                    $this->serializeLocales($value);
                break;
                default:
                    $this->getXmlWriter()->startElement($name);
                    $this->delegate($value);
                    $this->getXmlWriter()->endElement();
            }

        }
    }

    /**
     * Сериализует локали в XML
     * @param array $locales
     */
    protected function serializeLocales(array $locales) {
        $this->getXmlWriter()->startElement('locales');

        foreach ($locales as $localeId => $localeInfo) {
            $this->getXmlWriter()->startElement('locale');
            $this->writeAttribute('id', $localeId);
            if (isset($localeInfo['url'])) {
                $this->getXmlWriter()->writeAttribute('url', $localeInfo['url']);
            }
            if (isset($localeInfo['current'])) {
                $this->getXmlWriter()->writeAttribute('current', (int) $localeInfo['current']);
            }
            $this->getXmlWriter()->endElement();
        }

        $this->getXmlWriter()->endElement();
    }
}
