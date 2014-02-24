<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin;

use umi\config\entity\IConfig;
use umi\hmvc\component\Component;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Request;
use umi\http\Response;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\exception\UnexpectedValueException;
use umicms\project\config\IAdminSettingsAware;
use umicms\project\config\TAdminSettingsAware;
use umicms\serialization\ISerializationAware;
use umicms\serialization\TSerializationAware;

/**
 * Приложение административной панели.
 */
class AdminApplication extends Component implements IAdminSettingsAware, IToolkitAware, ISerializationAware
{
    use TAdminSettingsAware;
    use TToolkitAware;
    use TSerializationAware;

    /**
     * Имя опции для задания настроек.
     */
    const OPTION_SETTINGS = 'settings';
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
        if (isset($context->getRouteParams()[self::MATCH_COMPONENT])) {
            $this->currentRequestFormat = $this->getRequestFormatByPostfix($request->getRequestFormat(null));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function onDispatchResponse(IDispatchContext $context, Response $response)
    {
        if (isset($context->getRouteParams()[self::MATCH_COMPONENT])) {
            $view = $response->getContent();
            $serializer = $this->getSerializer($this->currentRequestFormat, $view);
            $serializer->init();
            $serializer($view);
            $response->setContent($serializer->output());
        }
    }

    /**
     * Производит определение формата запроса по постфиксу
     * @param string $postfix
     * @throws HttpNotFound если постфикс запроса не поддерживается приложением
     * @return string
     */
    protected function getRequestFormatByPostfix($postfix)
    {
        if (!in_array($postfix, $this->supportedRequestPostfixes)) {
            throw new HttpNotFound($this->translate(
                'Url postfix "{postfix}" is not supported.',
                ['postfix' => $postfix]
            ));
        }

        return $postfix;
    }

    /**
     * Регистрирует сервисы для работы административной панели.
     */
    protected function registerAdminSettings()
    {
        $settings = isset($this->options[self::OPTION_SETTINGS]) ? $this->options[self::OPTION_SETTINGS] : null;

        if (!$settings instanceof IConfig) {
            throw new UnexpectedValueException($this->translate(
                'Administration panel settings should be instance of IConfig.'
            ));
        }
        $this->setAdminSettings($settings);

        $this->getToolkit()->registerAwareInterface(
            'umicms\project\config\IAdminSettingsAware',
            function ($object) use ($settings) {
                if ($object instanceof IAdminSettingsAware) {
                    $object->setAdminSettings($settings);
                }
            }
        );
    }

}
