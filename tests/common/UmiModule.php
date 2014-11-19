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

use AspectMock\Test;
use Codeception\Lib\Framework;
use Codeception\TestCase;
use umi\http\Request;
use umi\orm\collection\ICollection;
use umi\orm\persister\IObjectPersister;
use umi\toolkit\IToolkit;
use umicms\module\IModule;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * This module extends Codeception framework
 * for testing UMI.CMS projects.
 */
class UmiModule extends Framework
{
    /**
     * Locale for test localized strings.
     * @var string $currentLocale
     */
    protected $locale = '';

    /**
     * Project url.
     * @var string $projectUrl
     */
    protected $projectUrl = '';
    /**
     * @var ICollection[] $fixtureObjects [guid => collection instance, ...]
     */
    protected $ormFixtureObjects = [];
    /**
     * @var IToolkit $commonToolkit common toolkit for all requests.
     */
    protected $commonToolkit;

    /**
     * {@inheritdoc}
     */
    public function _initialize() {
        $this->locale = $this->config['locale'];
        $this->projectUrl = $this->config['projectUrl'];

        $this->initializeCommonToolkit();
        $this->initializeUrlMap();
    }

    /**
     * {@inheritdoc}
     */
    public function _before(TestCase $test)
    {
        $this->client = new UmiConnector();
        $this->client->followRedirects(true);
        $this->initializeMocks();
    }

    /**
     * {@inheritdoc}
     */
    public function _after(TestCase $test)
    {
        Test::clean();
        $this->clearFixtureObjects();
    }

    /**
     * Persist UMI.CMS transaction to database
     */
    public function haveCommitTransaction()
    {
        /**
         * @var IObjectPersister $objectPersister
         */
        $objectPersister = $this->grabService('umi\orm\persister\IObjectPersister');

        $this->ormFixtureObjects = [];

        foreach ($objectPersister->getNewObjects() as $object) {
            $this->ormFixtureObjects[$object->getGUID()] = $object->getCollection();
        }

        $objectPersister->commit();
    }

    /**
     * Create new registered user. TestUser by default.
     * @param string|null $userName use as login, password and displayName
     * @return \umicms\project\module\users\model\object\RegisteredUser
     */
    public function haveRegisteredUser($userName = 'TestUser')
    {
        /**
         * @var RegisteredUser $user
         */
        $user = $this->grabUsersModule()
            ->user()
            ->add(RegisteredUser::TYPE_NAME);
        $user->login = $userName;
        $user->password = $userName;
        $user->displayName = $userName;

        $this->grabUsersModule()->register($user);
        $user->active = true;

        $this->haveCommitTransaction();
    }

    /**
     * Check if current page contains the text specified for current locale.
     * Specify the css selector to match only specific region.
     * Examples:
     * ``` php
     * <?php
     *     $I->seeLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout']); // I can suppose user is logged in
     * ?>
     * ```
     * @param array $texts text for each locale
     * @param null $selector
     * @see \Codeception\Lib\InnerBrowser::see()
     */
    public function seeLocalized(array $texts, $selector = null) {
        $this->see($this->getLocalized($texts), $selector);
    }

    /**
     * Checks if there is a link with text specified for current locale.
     * Specify url to match link with exact this url.
     * Examples:
     *
     * ``` php
     * <?php
     * $I->seeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout']); // matches <a href="#">Logout</a>
     * $I->seeLinkLocalized(['ru-RU' => 'Выйти', 'en-US' => 'Logout'],'/logout'); // matches <a href="/logout">Logout</a>
     * ?>
     * ```
     * @param array $texts text for each locale
     * @param null $url
     */
    public function seeLinkLocalized(array $texts, $url = null)
    {
        $this->seeLink($this->getLocalized($texts), $url);
    }

    /**
     * Grabs a service from common Toolkit
     * @param string $serviceInterfaceName
     * @param null|string $concreteClassName prepare realization
     * @return object
     */
    public function grabService($serviceInterfaceName, $concreteClassName = null)
    {
        return $this->commonToolkit->getService($serviceInterfaceName, $concreteClassName);
    }

    /**
     * Grabs a UMI.CMS module from common Toolkit
     * @param string $moduleClassName
     * @return IModule
     */
    public function grabModule($moduleClassName)
    {
        return $this->grabService('umicms\module\IModule', $moduleClassName);
    }

    /**
     * Grabs a UMI.CMS UsersModule from common Toolkit
     * @return UsersModule
     */
    public function grabUsersModule()
    {
        return $this->grabModule(UsersModule::className());
    }

    /**
     * Returns localized from text array
     * @param array $texts
     * @throws \UnexpectedValueException if undefined localization
     */
    protected function getLocalized(array $texts)
    {
        if (!isset($texts[$this->locale])) {
            throw new \UnexpectedValueException('Cannot find localization for locale "' . $this->locale . '".');
        }

        return $texts[$this->locale];
    }

    /**
     * Remove all objects, created by ORM form test.
     */
    protected function clearFixtureObjects()
    {
        if ($this->ormFixtureObjects) {
            foreach ($this->ormFixtureObjects as $guid => $collection) {
                $collection->delete($collection->get($guid));
            }

            $this->grabService('umi\orm\persister\IObjectPersister')
                ->commit();

            $this->ormFixtureObjects = [];
        }
    }

    /**
     * Add project url for all map
     */
    private function initializeUrlMap()
    {
        foreach (get_class_vars('umitest\UrlMap') as $name => $value) {
            $constantName = strtoupper(
                preg_replace(
                    '/(?!^)[[:upper:]][[:lower:]]/',
                    '$0',
                    preg_replace('/(?!^)[[:upper:]]+/', '_$0', $name)
                )
            );

            UrlMap::${$name} = $this->projectUrl . constant("umitest\\UrlMap::{$constantName}");
        }
    }

    /**
     * Initialize common toolkit (for all tests)
     */
    protected function initializeCommonToolkit()
    {
        /**
         * @var Request $request
         */
        $request = Request::create($this->projectUrl);

        $bootstrap = new Bootstrap($request);
        $bootstrap->dispatchProject();

        $this->commonToolkit = $bootstrap->getToolkit();
    }

    /**
     * Initialize mock for testing
     */
    protected function initializeMocks()
    {
        Test::double(
            'umicms\form\element\Captcha',
            [
                'validate' => function () {
                    return true;
                }
            ]
        );

    }

}
 