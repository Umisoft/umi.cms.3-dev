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
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\TCmsCollection;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\admin\component\AdminComponent;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * Приложение административной панели.
 */
class ApiApplication extends AdminComponent implements ISerializationAware, IToolkitAware
{
    use TSerializationAware;
    use TToolkitAware;

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
    protected $supportedRequestPostfixes = ['json', 'xml'];

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
        /**
         * @var ISerializerFactory $serializerFactory
         */
        $serializerFactory = $this->getToolkit()->getService('umicms\serialization\ISerializerFactory');

        $types = [
            ISerializerFactory::TYPE_XML => [
                'umicms\orm\object\CmsObject' => 'umicms\serialization\xml\object\CmsObjectSerializer',
                'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\xml\object\CmsElementSerializer',
                'umi\orm\metadata\field\BaseField' => 'umicms\serialization\xml\object\FieldSerializer'
            ],
            ISerializerFactory::TYPE_JSON => [
                'umi\orm\collection\BaseCollection' => 'umicms\serialization\json\orm\CollectionSerializer',
                'umi\orm\metadata\Metadata' => 'umicms\serialization\json\orm\MetadataSerializer',
                'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
                'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
                'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsAdminObjectSerializer',
                'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsAdminObjectSerializer',
                'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer',
                // form
                'umi\form\fieldset\FieldSet' => 'umicms\serialization\json\form\FieldSetSerializer',
                'umi\form\element\BaseFormElement' => 'umicms\serialization\json\form\BaseFormElementSerializer',
            ]
        ];

        $serializerFactory->registerSerializers($types);
    }

}
