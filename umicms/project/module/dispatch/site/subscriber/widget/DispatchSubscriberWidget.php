<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\site\subscriber\widget;

use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\dispatch\model\DispatchModule;
use umicms\project\module\dispatch\model\object\Subscriber;

/**
 * Виджет вывода выпуска.
 */
class DispatchSubscriberWidget extends BaseFormWidget
{

    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'form';

    /**
     * @var DispatchModule $module модуль "Подписчики"
     */
    protected $module;

    /**
     * Конструктор.
     * @param DispatchModule $module модуль "Подписчики"
     */
    public function __construct(DispatchModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getForm()
    {
        return $this->module->subscriber()->getForm(Subscriber::FORM_SUBSCRIBE_SITE, Subscriber::TYPE_NAME)
            ->setAction($this->getUrl('subscriber'));
    }
}
