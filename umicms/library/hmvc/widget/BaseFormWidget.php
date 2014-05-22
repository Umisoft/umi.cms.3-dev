<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\widget;

use umi\form\element\IFormElement;
use umi\form\IForm;

/**
 * Базовый класс виджета вывода формы.
 */
abstract class BaseFormWidget extends BaseSecureWidget
{
    /**
     * Имя инпута для хранения URL для редиректа после успешной обработки формы
     */
    const INPUT_REDIRECT_URL = 'redirectUrl';
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'form';
    /**
     * @var string $redirectUrl URL для редиректа после успешной обработки формы
     */
    public $redirectUrl;

    /**
     * Возвращает форму для отображения
     * @return IForm
     */
    abstract protected function getForm();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $form = $this->getForm();

        if ($form->has(self::INPUT_REDIRECT_URL)) {

            /**
             * @var IFormElement $redirectUrlInput
             */
            $redirectUrlInput = $form->get(self::INPUT_REDIRECT_URL);
            $redirectUrl = $this->redirectUrl ?: $this->getUrlManager()->getCurrentUrl(true);

            $redirectUrlInput->setValue($redirectUrl);
        }

        return $this->createResult(
            $this->template,
            [
                'form' => $form->getView()
            ]
        );
    }

}
 