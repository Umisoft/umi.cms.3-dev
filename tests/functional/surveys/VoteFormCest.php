<?php
namespace umitest\surveys;

use AspectMock\Test;
use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты формы голосования
 */
class VoteFormCest
{

    /**
     * @param FunctionalTester $I
     */
    public function checkVoteFormValidators(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Ответы',
                'en-US' => 'Answers'
            ],
            BlockMap::VOTE_ANSWERS
        );
        $I->submitForm(
            BlockMap::VOTE_FORM,
            []
        );
        $I->seeCurrentUrlEquals(UrlMap::$surveysNextShow);
        $I->seeLocalized(
            [
                'ru-RU' => 'Введите код с картинки',
                'en-US' => 'Captcha'
            ],
            BlockMap::CAPTCHA
        );
    }

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
}