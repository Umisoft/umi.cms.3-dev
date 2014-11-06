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
use umicms\project\module\dispatches\model\object\Template;
use umicms\hmvc\component\site\BaseSitePageController;

/**
 * Контроллер вывода выпуска рассылки
 */
class IndexController extends BaseSitePageController
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
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница
     * @templateParam Subscription $subscription - подписка
     * @templateParam Subscriber $subscriber - подписчик
     * @templateParam Release $release - выпуск рассылки
     * @return Response
     */
    public function __invoke()
    {
        /**
         * @var string $token - токен подписки
        */
        $token = $this->getRouteVar('token');

        /**
         * @var integer $id - id выпуска рассылки
        */
        $id = $this->getRouteVar('id');

        $release = $this->module->release()->getById($id);
        $subscription = $this->module->subscription()->getSubscriptionByToken($token);

        /**
         * @var Template $templateRelease - шаблон выпуска рассылки
        */
        $templateRelease = $release->getProperty(Release::FIELD_TEMPLATE)->getValue();

        $this->template = ($templateRelease && $templateRelease->getProperty(Template::FIELD_FILE_NAME)->getValue()) ?
            $templateRelease->getProperty(Template::FIELD_FILE_NAME)->getValue() :
            $this->template;

        return $this->createViewResponse(
            $this->template,
            [
                'page' => $this->getCurrentPage(),
                'subscription' => $subscription,
                'subscriber' => $subscription->getProperty(Subscription::FIELD_SUBSCRIBER)->getValue(),
                'release' => $release
            ]
        )->setIsCompleted();
    }

} 