<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\form\element;

use umi\form\element\BaseFormElement;
use umi\form\element\BaseFormInput;
use umi\form\FormEntityView;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umicms\captcha\ICaptchaAware;
use umicms\captcha\TCaptchaAware;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;

/**
 * Поле типа captcha.
 */
class Captcha extends BaseFormInput implements ICaptchaAware, ISessionAware, IUrlManagerAware
{
    use TCaptchaAware;
    use TSessionAware;
    use TUrlManagerAware;

    /**
     * Тип элемента.
     */
    const TYPE_NAME = 'captcha';

    /**
     * {@inheritdoc}
     */
    protected $type = 'captcha';
    /**
     * @var string $value фраза каптчи, введенная пользователем
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    protected function extendView(FormEntityView $view)
    {
        parent::extendView($view);

        $sessionKey = $this->getSessionKey();

        if (!$this->hasSessionVar($sessionKey)) {
            $options = $this->getOptions();
            $this->setSessionVar($sessionKey, $options);
        }

        $view->isHuman = $this->validate($this->value);
        $view->sessionKey = $sessionKey;

        $url = rtrim($this->getUrlManager()->getProjectUrl(), '/');
        $view->url = $url . '/captcha/' . rawurlencode($sessionKey);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate()
    {
        $sessionKey = $this->getSessionKey();
        $options = $this->getSessionVar($sessionKey, []);

        $result = false;
        if (isset($options['phrase'])) {
            $result = $this->getCaptchaGenerator()->test($options['phrase'], $this->getValue());
        }

        if (!$result) {
            $this->messages = ['Invalid captcha test.'];
        }

        return $result;
    }

    /**
     * Генерирует и возвращает уникальный ключ для хранения фразы в сессии.
     * @return string
     */
    protected function getSessionKey()
    {
        $names = $this->getName();

        $element = $this->getParent();
        while ($parent = $element->getParent()) {
            $names .= $parent->getName();
            $element = $parent;
        }

        return 'c_' . md5($names);
    }

    /**
     * Возвращает имя контейнера сессии.
     * @return string
     */
    protected function getSessionNamespacePath()
    {
        return 'umicms\captcha';
    }

}