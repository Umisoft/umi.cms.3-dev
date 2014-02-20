<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\widget;

use Exception;
use umicms\exception\UnexpectedValueException;
use umicms\widget\BaseWidget;

/**
 * Виджет для вывода ошибки виджетов.
 */
class ErrorWidget extends BaseWidget
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

        $e = $this->exception;
        $stack = [];

        while ($e = $e->getPrevious()) {
            $stack[] = $e;
        }

        return $this->createResult(
            'error/widget',
            [
                'e' => $this->exception,
                'stack' => $stack
            ]
        );
    }
}