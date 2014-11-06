<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\release\controller;

use umi\http\Response;
use umicms\project\module\dispatches\model\DispatchModule;
use umicms\project\module\dispatches\model\object\Release;
use umicms\project\module\dispatches\model\object\Subscription;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\hmvc\component\BaseCmsController;

/**
 * Контроллер подписки пользователя
 */
class ImageSrcController extends BaseCmsController
{

    /**
     * @var DispatchModule $module модуль "Рассылки"
     */
    protected $module;

    /**
     * Конструктор.
     * @param DispatchModule $module модуль "Рассылки"
     */
    public function __construct(DispatchModule $module)
    {
        $this->module = $module;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        /**
         * @var string $token - токен подписки
         */
        $token = $this->getRouteVar('token');
        /**
         * @var string $guidRelease - GUID выпуска рассылки
         */
        $guidRelease = $this->getRouteVar('release');

        /**
         * @var Release $release выпуска рассылки
         */
        $release = $this->module->release()->get($guidRelease);
        /**
         * @var Subscription $subscription - подписка
        */
        $subscription = $this->module->subscription()->getSubscriptionByToken($token);
        /**
         * @var Subscriber $subscriber - подписка
         */
        $subscriber = $subscription->getProperty(Subscription::FIELD_SUBSCRIBER)->getValue();
        $this->module->setReadLogDispatch($release, $subscriber);

        /**
         * @var $image - однопиксельная картинка
        */
        $image = @imagecreate(1, 1);
        /**
         * @var $white - задать белый цвет для изображения
        */
        $white = imagecolorallocate($image, 255, 255, 255);
        /**
         * задать прозрачность изображения
        */
        imagecolortransparent($image, $white);
        /**
         * @var Response $response
        */
        $response = $this->createResponse('');
        $response->headers->set('Content-Type', 'image/png');
        imagepng($image);
        /**
         * Освободить память
        */
        imagedestroy($image);

        return $response->setIsCompleted();
    }
}