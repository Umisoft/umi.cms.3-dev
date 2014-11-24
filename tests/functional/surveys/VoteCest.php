<?php
namespace umitest\surveys;

use AspectMock\Test;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты на голосование.
 */
class VoteCest
{
   /**
     * @param FunctionalTester $I
     */
    public function SuccessVote(FunctionalTester $I)
    {
        Test::double(
            'umicms\form\element\Captcha',
            [
                'validate' => function () {
                    return true;
                }
            ]
        );

        $I->amOnPage(UrlMap::$defaultUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Ответы',
                'en-US' => 'Answers'
            ],
            '.answers label'
        );
        $I->submitForm(
            '#surveys_voteForm',
            [
                'answers' => '3617fe64-54cf-4354-9031-4b5674abae6e'
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl . '/surveys/nextshow');
        $I->cantSeeElement('.captcha');
        $I->seeElement('.progress-bar');
        $I->seeLocalized(
            [
                'ru-RU' => 'Всего голосов',
                'en-US' => 'Total votes'
            ],
            '.blog-post'
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function FailureVote(FunctionalTester $I)
    {
        Test::double(
            'umicms\form\element\Captcha',
            [
                'validate' => function () {
                    return false;
                }
            ]
        );

        $I->amOnPage(UrlMap::$defaultUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Ответы',
                'en-US' => 'Answers'
            ],
            '.answers label'
        );
        $I->submitForm(
            '#surveys_voteForm',
            []
        );
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl . '/surveys/nextshow');
        $I->seeLocalized(
            [
                'ru-RU' => 'Введите код с картинки',
                'en-US' => 'Captcha'
            ],
            '.captcha'
        );
    }

    public function AlreadyVoted(FunctionalTester $I)
    {
        $I->setCookie('307064ad-f397-42f5-9b19-d92c65990429', 1);

        $I->amOnPage(UrlMap::$defaultUrl);

        $I->cantSeeElement('.captcha');
        $I->seeLocalized(
            [
                'ru-RU' => 'Всего голосов',
                'en-US' => 'Total votes'
            ],
            '.blog-sidebar'
        );

    }
}