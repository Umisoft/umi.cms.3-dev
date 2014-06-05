<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umicms\captcha\ICaptchaAware;
use umicms\captcha\TCaptchaAware;
use umicms\hmvc\controller\BaseController;

/**
 * Контроллер вывода каптчи.
 */
class CaptchaController extends BaseController implements ICaptchaAware, ISessionAware
{
    use TCaptchaAware;
    use TSessionAware;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $key = $this->getRouteVar('key');

        if (!$this->hasSessionVar($key)) {
            return $this->createResponse('Cannot generate captcha. Invalid session key.', Response::HTTP_NOT_FOUND)
               ->setIsCompleted();
        }
        $options = $this->getSessionVar($key);

        $generator = $this->getCaptchaGenerator();
        $options['phrase'] = $generator->generatePhrase();

        $this->setSessionVar($key, $options);

        $response = $this->createResponse(
            $generator->generate($options['phrase'], $options)
        );

        $response->headers->set('Content-type', 'image/jpeg');
        $response->setIsCompleted();

        return $response;

    }

    /**
     * Возвращает имя контейнера сессии.
     * @return string
     */
    protected function getSessionNamespacePath()
    {
        return 'umicms\captcha';
    }
}
 