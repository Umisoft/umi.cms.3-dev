<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\hmvc\exception\http\HttpNotFound;
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
        $reload = $this->getQueryVar('reload');

        if (!$this->hasSessionVar($key)) {
            return $this->createResponse('Cannot generate captcha. Invalid session key.', Response::HTTP_NOT_FOUND)
               ->setIsCompleted();
        }
        $options = $this->getSessionVar($key);

        $generator = $this->getCaptchaGenerator();

        if ($reload || !isset($options['phrase'])) {
            $options['phrase'] = $generator->generatePhrase();
        }

        $this->setSessionVar($key, $options);

        $response = $this->createResponse(
            $generator->generate($options['phrase'], $options)
        );

        $response->headers->set('Content-type', 'image/jpeg');
        $response->setIsCompleted();

        return $response;

    }
}
 