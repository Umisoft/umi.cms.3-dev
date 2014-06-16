<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\settings\component;

use umicms\hmvc\component\BaseCmsComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * Компонент настроек.
 */
class SettingsComponent extends BaseCmsComponent implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        $result = [
            'name'        => $this->getName(),
            'displayName' => $this->translate('component:' . $this->getName() . ':displayName'),
        ];

        if ($this->hasController('index')) {
            $result['resource'] = $this->getUrlManager()->getSettingsComponentResourceUrl($this);
        }

        return $result;
    }

}
 