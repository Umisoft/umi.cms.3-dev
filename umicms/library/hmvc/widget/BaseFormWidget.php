<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\hmvc\component\IComponent;
use umicms\hmvc\view\CmsView;

/**
 * Базовый класс виджета вывода формы.
 */
abstract class BaseFormWidget extends BaseCmsWidget
{
    /**
     * Имя инпута для хранения URL для редиректа после успешной обработки формы
     */
    const INPUT_REDIRECT_URL = 'redirectUrl';

    /**
     * Не выполнять редирект в случае успешного сохранения формы
     */
    const NO_REDIRECT = 'noRedirect';
    /**
     * Выполнять редирект на URL источника запроса
     */
    const REFERER_REDIRECT = 'refererRedirect';

    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'form';
    /**
     * @var string $redirectUrl URL для редиректа после успешной обработки формы
     */
    public $redirectUrl = self::NO_REDIRECT;
    /**
     * @var int $formCounters
     */
    protected static $formCounters;

    /**
     * Возвращает форму для отображения
     * @return IForm
     */
    abstract protected function getForm();

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umi\form\FormEntityView $form представление формы
     * @templateParam string $formId уникальный идентификатор формы
     *
     * @return CmsView
     */
    public function __invoke()
    {
        $form = $this->getForm()->setId($this->getUniqueFormId());

        if ($form->has(self::INPUT_REDIRECT_URL)) {

            /**
             * @var IFormElement $redirectUrlInput
             */
            $redirectUrlInput = $form->get(self::INPUT_REDIRECT_URL);
            $redirectUrl = ($this->redirectUrl === self::REFERER_REDIRECT) ?
                $this->getUrlManager()->getCurrentUrl(true) : $this->redirectUrl;

            $redirectUrlInput->setValue($redirectUrl);
        }

        $result = (array) $this->buildResponseContent();
        $result['form'] = $form->getView();

        return $this->createResult(
            $this->template,
            $result
        );
    }

    /**
     * Возвращает дополнительные (помимо самой формы) переменные для шаблонизации
     * @return array
     */
    protected function buildResponseContent()
    {
        return [];
    }

    protected function getFormIdPostfix()
    {
        if (isset(self::$formCounters[get_class($this)])) {
            return '_' . ++self::$formCounters[get_class($this)];
        } else {
            self::$formCounters[get_class($this)] = 0;
            return '';
        }
    }

    protected function getUniqueFormId()
    {
        return str_replace(IComponent::PATH_SEPARATOR, '_', $this->getShortPath()) . $this->getFormIdPostfix();
    }

}
 