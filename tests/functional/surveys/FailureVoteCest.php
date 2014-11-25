<?php
namespace umitest\surveys;

use AspectMock\Test;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты на голосование.
 */
class FailureVoteCest
{
    /**
     * @param FunctionalTester $I
     * @throws \Exception
     */
    public function _before(FunctionalTester $I)
    {
        Test::double(
            'umicms\form\element\Captcha',
            [
                'validate' => function () {
                    return false;
                }
            ]
        );
    }

    /**
     * @param FunctionalTester $I
     */
    public function checkVoteFormValidators(FunctionalTester $I)
    {
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
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl . UrlMap::SURVEYS_NEXT_SHOW);
        $I->seeLocalized(
            [
                'ru-RU' => 'Введите код с картинки',
                'en-US' => 'Captcha'
            ],
            '.captcha'
        );
    }
}