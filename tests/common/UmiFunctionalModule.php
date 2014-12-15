<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

/**
 * TODO необходимо пренести все функциональные аспекты из \umitest\UmiModule в этот класс
 * Class UmiFunctionalModule
 *
 * Функциональный тестер для UMI.CMS
 *  - мок для отправки почты
 *  - asserts с локализацией
 *
 * @package umitest
 */
class UmiFunctionalModule extends UmiModule
{

    /**
     * Проверяет, содержит ли текущая страница текст в текущей локали.
     * В тексте можно использовать плейсхолдеры, например для указания url из UrlMap:
     * ['ru-RU' => 'Абсолютный url проекта: {projectAbsoluteUrl}', ...]
     * @see \Codeception\Lib\InnerBrowser::see()
     * @param array $texts массив в формате ['RU-ru' => 'текст', 'En-us' => 'текст', ...] для каждой локали
     * @param null  $selector
     */
    public function seeLocalized(array $texts, $selector = null)
    {
        $this->see($this->getLocalized($texts), $selector);
    }

    /**
     * Проверяет, что текущая страница не содержит текст в текущей локали.
     * В тексте можно использовать плейсхолдеры, например для указания url из UrlMap:
     * ['ru-RU' => 'Абсолютный url проекта: {projectAbsoluteUrl}', ...]
     * @see \Codeception\Lib\InnerBrowser::dontSee()
     * @param array        $texts массив в формате ['RU-ru' => 'текст', 'En-us' => 'текст', ...] для каждой локали
     * @param null|string  $selector
     */
    public function dontSeeLocalized(array $texts, $selector = null)
    {
        $this->dontSee($this->getLocalized($texts), $selector);
    }

    /**
     * Checks if there is a link with text specified for current locale.
     * Specify url to match link with exact this url.
     * Examples:
     * ``` php
     * <?php
     *   $I->seeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout']); // matches <a href="#">Logout</a>
     *   $I->seeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout'],'/logout'); // matches <a href="/logout">Logout</a>
     * ?>
     * ```
     * @param array $texts text for each locale
     * @param null  $url
     */
    public function seeLinkLocalized(array $texts, $url = null)
    {
        $this->seeLink($this->getLocalized($texts), $this->replaceUrlPlaceholders($url));
    }

    /**
     * Кликает по эелементу, учитывая текущую локаль и контекст (если задан)
     * Examples:
     * ``` php
     * <?php
     *   $I->clickLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout'], '.nav'); // matches <a href="#">Logout</a>
     * ?>
     * ```
     * @param array       $texts   text for each locale
     * @param string|null $context
     */
    public function clickLocalized(array $texts, $context = null)
    {
        $this->click($this->getLocalized($texts), $context);
    }

    /**
     * Checks that there are no links with text specified for current locale.
     * Specify url to match link with exact this url.
     * Examples:
     * ``` php
     * <?php
     *   $I->dontSeeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout']); // matches <a href="#">Logout</a>
     *   $I->dontSeeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout'],'/logout'); // matches <a href="/logout">Logout</a>
     * ?>
     * ```
     * @param array       $texts text for each locale
     * @param null|string $url
     */
    public function dontSeeLinkLocalized(array $texts, $url = null)
    {
        $this->dontSeeLink($this->getLocalized($texts), $url);
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function seeHttpHeader($header, $value) {
        $this->assertTrue($this->client->getResponse()->headers->contains($header, $value));
    }

    /**
     * @param string $email
     * @param array  $localizedSubject
     */
    public function openEmailMessage($email, array $localizedSubject)
    {
        $this->haveEmailMessage($email, $localizedSubject);

        $subject = $this->getLocalized($localizedSubject);
        $this->amOnPage("/messages?email={$email}&subject={$subject}");
    }

    /**
     * @param string $email
     * @param array  $localizedSubject
     */
    public function haveEmailMessage($email, array $localizedSubject)
    {
        $subject = $this->getLocalized($localizedSubject);
        $this->assertTrue($this->messageBox->has($email, $subject));
    }

    /**
     * Отклюяает редиректы в PhpBrowser
     */
    public function dontFollowRedirects()
    {
        $this->client->followRedirects(false);
    }

    /**
     * Returns localized from text array
     * @param array $texts
     * @return string
     * @throws \UnexpectedValueException if undefined localization
     */
    protected function getLocalized(array $texts)
    {
        if (!isset($texts[$this->locale])) {
            throw new \UnexpectedValueException('Cannot find localization for locale "' . $this->locale . '".');
        }

        return strtr($texts[$this->locale], $this->localePlaceholders);
    }

} 