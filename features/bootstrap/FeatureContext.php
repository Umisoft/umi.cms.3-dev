<?php

require_once 'bootstrap.php';
require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

use Behat\MinkExtension\Context\MinkContext;
use umi\config\io\TConfigIOAware;
use umi\http\Request;
use umi\orm\persister\IObjectPersister;
use umicms\hmvc\component\admin\settings\SettingsComponent;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

class FeatureContext extends MinkContext
{
    use TConfigIOAware;
    /**
     * @var UsersModule $usersModule
     */
    private $usersModule;

    /**
     * @var IObjectPersister $objectPersister
     */
    private $objectPersister;

    /**
     * @var SettingsComponent
     */
    private $settings;

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
        $this->settings = new SettingsComponent(
            'registration',
            'project.admin.rest.settings.users.registration',
            array(
                'settingsConfigAlias' => '~/project/module/users/configuration/user/collection.settings.config.php',
            )
        );
        $this->setConfigIO($toolkit->getService('umi\config\io\IConfigIO'));
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
        $users = $userCollection->getUsersByLoginPart('minktest');
        foreach ($users as $user) {
            $userCollection->delete($user);
        }

        $this->objectPersister->commit();
        @unlink('public/messages.txt');
        $this->activationIsTurned('on');
    }

    /**
     * @Given /^I have "([^"]*)" language$/
     */
    public function iHaveLanguage($langId)
    {
        $this->clickLink($langId);
    }

    /**
     * @Given /^Activation is turned "([^"]*)"$/
     */
    public function activationIsTurned($value)
    {
        $config = $this->readConfig($this->settings->getSettingsConfigAlias());
        $config->set('registrationWithActivation', (bool)('on' == $value));
        $this->writeConfig($config);
    }

    /**
     * @When /^I follow activation from email$/
     */
    public function iFollowActivationFromEmail()
    {
        $message = file_get_contents('public/messages.txt');
        assertEquals(1, preg_match('/>(http.*)<\/a/', $message, $matches));
        $this->visit($matches[1]);
    }

}