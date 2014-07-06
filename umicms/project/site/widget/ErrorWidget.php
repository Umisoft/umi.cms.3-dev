<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\widget;

use Exception;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\Environment;
use umicms\serialization\ISerializer;

/**
 * Виджет для вывода ошибки виджетов.
 */
class ErrorWidget extends BaseCmsWidget
{

    /**
     * @var Exception $exception
     */
    public $exception;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        if (!$this->exception instanceof Exception) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot execute error widget. Expected "exception" parameter.'
                )
            );
        }

        $code = $this->getExceptionStatusCode($this->exception);

        $e = $this->exception;
        $stack = [];

        if (Environment::$showExceptionStack) {
            while ($e = $e->getPrevious()) {
                $stack[] = $e;
            }
        }

        return $this->createResult(
            'error/widget',
            [
                'error' => $this->exception,
                'stack' => $stack,
                'code' => $code
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createResult($templateName, array $variables = []) {
        $variables['showStack'] = Environment::$showExceptionStack;
        $variables['showTrace'] = Environment::$showExceptionTrace;

        $view = parent::createResult($templateName, $variables);

        $view->addSerializerConfigurator(
            function(ISerializer $serializer)
            {
                $excludes = [
                    'showStack',
                    'showTrace'
                ];
                if (!Environment::$showExceptionStack) {
                    $excludes[] = 'stack';
                }
                $serializer->setExcludes($excludes);
            }
        );

        return $view;
    }
}