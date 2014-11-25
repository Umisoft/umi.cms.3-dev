<?php
namespace umitest\surveys;

use umitest\FunctionalTester;
use umitest\UrlMap;

/**
 * Тесты на голосование.
 */
class VoteCest
{
    const VOTE_GUID = '307064ad-f397-42f5-9b19-d92c65990429';
    const VOTE_ANSWER_GUID = '3617fe64-54cf-4354-9031-4b5674abae6e';

    /**
     * @param FunctionalTester $I
     */
    public function vote(FunctionalTester $I)
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
            [
                'answers' => self::VOTE_ANSWER_GUID
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl . UrlMap::SURVEYS_NEXT_SHOW);
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

    public function reVote(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$defaultUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Ответы',
                'en-US' => 'Answers'
            ],
            '.answers label'
        );
        $I->setCookie(self::VOTE_GUID, 1);
        $I->submitForm(
            '#surveys_voteForm',
            [
                'answers' => self::VOTE_ANSWER_GUID
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$defaultUrl . UrlMap::SURVEYS_NEXT_SHOW);

        $I->seeLocalized(
            [
                'ru-RU' => 'Вы уже проголосовали',
                'en-US' => 'You have already voted'
            ]
        );
    }

    public function viewResult(FunctionalTester $I)
    {
        $I->setCookie(self::VOTE_GUID, 1);

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