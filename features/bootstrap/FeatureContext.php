<?php

use Behat\MinkExtension\Context\MinkContext;
use umi\http\Request;
use umi\orm\persister\IObjectPersister;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

class FeatureContext extends MinkContext
{
    /**
     * @var UsersModule $usersModule
     */
    private $usersModule;

    /**
     * @var IObjectPersister $objectPersister
     */
    private $objectPersister;

    public function __construct()
    {
        $projectUri = 'http://umicms3.dkop/php';

        /**
         * @var Request $request
         */
        $request = Request::create($projectUri);

        $bootstrap = new Bootstrap($request);
        $bootstrap->dispatchProject();
        $toolkit = $bootstrap->getToolkit();
        $this->usersModule = $toolkit->getService('umicms\module\IModule', UsersModule::className());
        $this->objectPersister = $toolkit->getService('umi\orm\persister\IObjectPersister');
    }

    /**
     * @BeforeScenario
     */
    public function before($event)
    {
        /** @var RegisteredUser $user */
        $user = $this->usersModule->user()->add(RegisteredUser::TYPE_NAME);
        $user->groups->attach($this->usersModule->userGroup()->get('daabebf8-f3b3-4f62-a23d-522eff9b7f68'));
        $user->login = 'minktest';
        $user->setPassword('testpassword');
        $user->displayName = 'testuser';
        $user->active = true;

        $this->objectPersister->commit();
    }

    /**
     * @AfterScenario
     */
    public function after($event)
    {
        $userCollection = $this->usersModule->user();
        $user = $userCollection->getUserByLoginOrEmail('minktest');
        $userCollection->delete($user);

        $this->objectPersister->commit();
    }

    /**
     * @Given /^I have "([^"]*)" language$/
     */
    public function iHaveLanguage($langId)
    {
        $this->clickLink($langId);
    }

}