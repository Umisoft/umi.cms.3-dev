<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest;

use umi\acl\IAclFactory;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpForbidden;
use umi\http\Request;
use umi\http\Response;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\hmvc\controller\BaseCmsController;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\TCmsCollection;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\admin\component\AdminComponent;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * REST-часть приложения административной панели.
 */
class RestApplication extends AdminComponent implements ISerializationAware, IToolkitAware, ISessionAware
{
    use TSerializationAware;
    use TToolkitAware;
    use TSessionAware;

    /**
     * Опция для задания сериализаторов приложения
     */
    const OPTION_SERIALIZERS = 'serializers';
    /**
     * Формат запроса по умолчанию.
     */
    const DEFAULT_REQUEST_FORMAT = 'json';

    /**
     * @var string $currentRequestFormat формат запроса к приложению
     */
    protected $currentRequestFormat = self::DEFAULT_REQUEST_FORMAT;
    /**
     * @var array $supportedRequestPostfixes список поддерживаемых постфиксов запроса
     */
    protected $supportedRequestPostfixes = ['json'];

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $this->registerSelectorInitializer();
        $this->registerSerializers();

        $requestFormat = $request->getRequestFormat(self::DEFAULT_REQUEST_FORMAT);

        if (!$this->isRequestFormatSupported($requestFormat)) {
            $request->setRequestFormat(self::DEFAULT_REQUEST_FORMAT);

            throw new HttpException(Response::HTTP_BAD_REQUEST, $this->translate(
                'Request format "{format}" is not supported.',
                ['format' => $requestFormat]
            ));
        }

        $request->setRequestFormat($requestFormat);

        $this->currentRequestFormat = $requestFormat;

        if (!$this->checkCsrfToken($context, $request)) {
            throw new HttpForbidden('Cannot process request. Invalid csrf token.');
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchResponse(IDispatchContext $context, Response $response)
    {
        $result = [
            'result' => $response->getContent()
        ];

        $serializer = $this->getSerializer($this->currentRequestFormat, $result);
        $serializer->init();
        $serializer($result);
        $response->setContent($serializer->output());

        return $response;
    }

    /**
     * Проверяет csrf-токен запроса.
     * @param IDispatchContext $context
     * @param Request $request
     * @return bool
     */
    protected function checkCsrfToken(IDispatchContext $context, Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return true;
        }

        $params = $context->getRouteParams();

        if (isset($params['ignoreCsrf']) && $params['ignoreCsrf']) {
           return true;
        }

        $validToken = $this->getSessionVar('token');
        $requestToken = $request->headers->get('X-Csrf-Token');
        if ($requestToken && $requestToken === $validToken) {
            return true;
        }

        return false;
    }

    /**
     * Проверяет, поддерживается ли указанный формат запроса
     * @param string $format
     * @return bool
     */
    protected function isRequestFormatSupported($format)
    {
        return in_array($format, $this->supportedRequestPostfixes);
    }

    /**
     * Регистрирует иницициализотор для всех селекторов.
     */
    protected function registerSelectorInitializer()
    {
        TCmsCollection::setSelectorInitializer(function(CmsSelector $selector) {

            $collection = $selector->getCollection();

            if ($collection instanceof IRecyclableCollection) {
                $selector->where(IRecyclableObject::FIELD_TRASHED)->notEquals(true);
            }

        });
    }

    /**
     * Регистрирует сериализаторы, необходимые для приложения.
     */
    protected function registerSerializers()
    {
        if (isset($this->options[self::OPTION_SERIALIZERS])) {
            $serializersConfig = $this->configToArray($this->options[self::OPTION_SERIALIZERS], true);
            /**
             * @var ISerializerFactory $serializerFactory
             */
            $serializerFactory = $this->getToolkit()->getService('umicms\serialization\ISerializerFactory');

            $serializerFactory->registerSerializers($serializersConfig);
        }
    }

    /**
     * Возвращает имя контейнера сессии.
     * @return string
     */
    protected function getSessionNamespacePath()
    {
        return 'umicms';
    }

    /**
     * {@inheritdoc}
     */
    protected function getChildComponentsAclConfig()
    {
        $config = parent::getChildComponentsAclConfig();
        foreach ($config[IAclFactory::OPTION_RULES] as $resources) {
            foreach ($this->options[self::OPTION_CONTROLLERS] as $name => $class) {
                $resources[$name . BaseCmsController::ACL_RESOURCE_PREFIX] = [];
            }
        }
        return $config;
    }
}
