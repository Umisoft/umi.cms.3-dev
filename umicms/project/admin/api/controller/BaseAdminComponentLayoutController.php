<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\api\controller;

use umicms\project\admin\controller\base\BaseAdminController;
use umicms\project\admin\layout\AdminComponentLayout;

/**
 * Базовый контроллер сетки интерфейса административного компонента.
 */
abstract class BaseAdminComponentLayoutController extends BaseAdminController
{
    /**
     * Возвращет сетку интерфейса компонента.
     * @return AdminComponentLayout
     */
    abstract protected function getLayout();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'layout',
            [
                'layout' => $this->getLayout()->build()
            ]
        );
    }
}
 