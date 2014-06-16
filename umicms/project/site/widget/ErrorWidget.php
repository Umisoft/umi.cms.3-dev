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

        $e = $this->exception;
        $stack = [];

        while ($e = $e->getPrevious()) {
            $stack[] = $e;
        }

        return $this->createResult(
            'error/widget',
            [
                'error' => $this->exception,
                'stack' => $stack
            ]
        );
    }
}