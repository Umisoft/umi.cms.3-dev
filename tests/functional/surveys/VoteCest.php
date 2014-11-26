<?php
namespace umitest\surveys;

use umitest\BlockMap;
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
            [
                'answers' => self::VOTE_ANSWER_GUID
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$surveysNextShow);
        $I->cantSeeElement('.captcha');
        $I->seeElement('.progress-bar');
        $I->seeLocalized(
            [
                'ru-RU' => 'Всего голосов',
                'en-US' => 'Total votes'
            ],
            BlockMap::BLOG_POST
        );
    }

    public function reVote(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
        $I->seeLocalized(
            [
                'ru-RU' => 'Ответы',
                'en-US' => 'Answers'
            ],
            BlockMap::VOTE_ANSWERS
        );
        $I->setCookie(self::VOTE_GUID, 1);
        $I->submitForm(
            BlockMap::VOTE_FORM,
            [
                'answers' => self::VOTE_ANSWER_GUID
            ]
        );
        $I->seeCurrentUrlEquals(UrlMap::$surveysNextShow);

        $I->seeLocalized(
            [
                'ru-RU' => 'Вы уже проголосовали',
                'en-US' => 'You have already voted'
            ]
        );
    }

    public function voteWithoutOption(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
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

        $I->seeCurrentUrlEquals(UrlMap::$surveysNextShow);

        $I->seeLocalized(
            [
                'ru-RU' => 'Не выбран ни один из вариантов',
                'en-US' => 'Do not select any option'
            ]
        );
    }

    public function voteWithFalseOption(FunctionalTester $I)
    {
        $I->amOnPage(UrlMap::$projectUrl);
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
                'answers' => 'false-option'
            ]
        );

        $I->seeCurrentUrlEquals(UrlMap::$surveysNextShow);

        $I->seeLocalized(
            [
                'ru-RU' => 'Value "false-option" is not in available values list',
                'en-US' => 'Value "false-option" is not in available values list'
            ]
        );
    }

    public function viewResult(FunctionalTester $I)
    {
        $I->setCookie(self::VOTE_GUID, 1);

        $I->amOnPage(UrlMap::$projectUrl);

        $I->cantSeeElement('.captcha');
        $I->seeLocalized(
            [
                'ru-RU' => 'Всего голосов',
                'en-US' => 'Total votes'
            ],
            BlockMap::BLOG_SIDEBAR
        );
    }
}