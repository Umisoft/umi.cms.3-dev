<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\release\widget;

use umicms\hmvc\widget\BaseLinkWidget;
use umicms\exception\InvalidArgumentException;
use umicms\project\module\dispatches\model\object\Release;
use umicms\project\module\dispatches\model\object\Subscription;
use umicms\project\module\dispatches\model\DispatchModule;

/**
 * Виджет для вывода ссылки на список рассылок
 */
class ImageSrcLinkWidget extends BaseLinkWidget
{
    /**
     * {@inheritdoc}
     */
    public $absolute = true;

    /**
     * {@inheritdoc}
     */
    public $template = 'imageSrc';

    /**
     * @var string|Release $release выпуск рассылки или GUID выпуса рассылки
     */
    public $release;

    /**
     * @var string|Subscription $subscription - подписка или GUID подписки
     */
    public $subscription;

    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * Конструктор.
     * @param DispatchModule $dispatchApi модуль "Рассылки"
     */
    public function __construct(DispatchModule $dispatchApi)
    {
        $this->module = $dispatchApi;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLinkUrl()
    {
        if (is_string($this->release)) {
            $this->release = $this->module->release()->get($this->release);
        }

        if (!$this->release instanceof Release){
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'release',
                        'class' => Release::className()
                    ]
                )
            );
        }

        if (is_string($this->subscription)) {
            $this->subscription = $this->module->subscription()->get($this->subscription);
        }

        if (!$this->subscription instanceof Subscription){
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'subscription',
                        'class' => Subscription::className()
                    ]
                )
            );
        }
        return $this->getUrl('imagesrc', ['release' => $this->release->getGUID(), 'token'=> $this->subscription->token], $this->absolute);
    }

}
 