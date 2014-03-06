<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpException;
use umi\http\Request;
use umi\http\Response;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\config\IAdminSettingsAware;
use umicms\project\admin\config\TAdminSettingsAware;
use umicms\serialization\ISerializationAware;
use umicms\serialization\TSerializationAware;

/**
 * Приложение административной панели.
 */
class ApiApplication extends AdminComponent implements IAdminSettingsAware, IToolkitAware, ISerializationAware
{
    use TAdminSettingsAware;
    use TToolkitAware;
    use TSerializationAware;

    /**
     * Формат запроса по умолчанию.
     */
    const DEFAULT_REQUEST_FORMAT = 'json';

    /**
     * @var string $requestFormat формат запроса к приложению
     */
    protected $currentRequestFormat = self::DEFAULT_REQUEST_FORMAT;

    /**
     * @var array $supportedRequestPostfixes список поддерживаемых постфиксов запроса
     */
    protected $supportedRequestPostfixes = ['json', 'xml'];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        parent::__construct($name, $path, $options);

        $this->registerAdminSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
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

        $options = [];

        $serializer($result, $options);
        $response->setContent($serializer->output());

        return $response;
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
     * Регистрирует сервисы для работы административной панели.
     */
    protected function registerAdminSettings()
    {

        $this->setAdminSettings($this->getSettings());

        $this->getToolkit()->registerAwareInterface(
            'umicms\project\admin\config\IAdminSettingsAware',
            function ($object) {
                if ($object instanceof IAdminSettingsAware) {
                    $object->setAdminSettings($this->getSettings());
                }
            }
        );
    }

}
