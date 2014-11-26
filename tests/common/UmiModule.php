<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

use AspectMock\Test;
use Codeception\Lib\Framework;
use Codeception\TestCase;
use Codeception\Util\Debug;
use Symfony\Component\HttpFoundation\Response;
use umi\dbal\cluster\IDbCluster;
use umi\dbal\toolbox\DbalTools;
use umi\http\Request;
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
     * @api
     * @var UmiConnector
     */
    public $client;

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
     * @var IToolkit $commonToolkit common toolkit.
     */
    protected $commonToolkit;
    /**
     * @var IDbCluster $commonDbCluster common db connection used for all requests.
     */
    protected $commonDbCluster;
    /**
     * @var MockMessageBox
     */
    public $messageBox;

    /**
     * {@inheritdoc}
     */
    public function _initialize()
    {
        $this->locale = $this->config['locale'];
        $this->projectUrl = $this->config['projectUrl'];

        $this->initializeCommonToolkit();
        $this->initializeUrlMap();
        $this->initializeMessageBox();
    }

    /**
     * {@inheritdoc}
     */
    public function _before(TestCase $test)
    {
        $this->initializeMocks();

        $this->client = new UmiConnector();
        $this->client->setMessageBox($this->messageBox);
        $this->client->followRedirects(true);

        $this->injectCommonServices();
        $this->beginDbTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function _after(TestCase $test)
    {
        if ($this->grabDbCluster()->getConnection()->isTransactionActive()) {
            $this->rollbackDbTransaction();
        }
        Test::clean();
        $this->messageBox->clean();
    }

    public function dontFollowRedirects()
    {
        $this->client->followRedirects(false);
    }

    public function seeHttpHeader($header, $value) {
        $this->assertTrue($this->client->getResponse()->headers->contains($header, $value));
    }

    public function openEmailMessage($email, array $localizedSubject)
    {
        $subject = $this->getLocalized($localizedSubject);
        $this->amOnPage("/messages?email={$email}&subject={$subject}");
    }

    public function haveEmailMessage($email)
    {
        $this->amOnPage("/messages?email={$email}");
        $this->assertEquals(Response::HTTP_OK, $this->getResponseStatusCode());
    }

    /**
     * Create new registered user. TestUser by default.
     * @param string|null $userName use as login, password and displayName
     * @return \umicms\project\module\users\model\object\RegisteredUser
     */
    public function haveRegisteredUser($userName = 'TestUser')
    {
        Debug::debug('Creating registered user "' . $userName  . "'");
        /**
         * @var RegisteredUser $user
         */
        $user = $this->grabUsersModule()
            ->user()
            ->add(RegisteredUser::TYPE_NAME);
        $user->login = $userName;
        $user->password = $userName;
        $user->displayName = $userName;

        $this->grabUsersModule()
            ->register($user);
        $user->active = true;

        $this->grabOrmObjectPersister()->commit();
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
    public function seeLocalized(array $texts, $selector = null)
    {
        $this->see($this->getLocalized($texts), $selector);
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
     * Grabs ORM Object persister from common Toolkit
     * @return IObjectPersister
     */
    public function grabOrmObjectPersister()
    {
        return $this->grabService('umi\orm\persister\IObjectPersister');
    }

    /**
     * Grabs DbCluster persister from common Toolkit
     * @return IDbCluster
     */
    public function grabDbCluster()
    {
        return $this->commonDbCluster;
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
     * Inject common services for any request
     */
    protected function injectCommonServices()
    {
        $this->client->setToolkitInitializer(function(IToolkit $toolkit) {
            /**
             * @var DbalTools $dbalTools
             */
            $dbalTools = $toolkit->getToolbox(DbalTools::NAME);
            $dbalTools->setCluster($this->commonDbCluster);
        });
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
     * Add project url for all map
     */
    protected function initializeUrlMap()
    {
        $reflection = new \ReflectionClass('umitest\UrlMap');
        $defaultProperties = $reflection->getDefaultProperties();

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC) as $property) {
            if (isset($defaultProperties[$property->name])) {
                $property->setValue($this->projectUrl . $defaultProperties[$property->name]);
            }
        }

        UrlMap::setProjectDomain($this->grabService('umicms\hmvc\url\IUrlManager')->getSchemeAndHttpHost());
    }

    /**
     * Start transaction for request
     */
    protected function beginDbTransaction()
    {
        $this->grabDbCluster()->getConnection()->beginTransaction();
    }

    /**
     * Rollback transaction
     */
    protected function rollbackDbTransaction()
    {
        Debug::debug('Rollback transaction');
        $this->grabDbCluster()->getConnection()->rollBack();
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
        $bootstrap->init();

        $this->commonToolkit = $bootstrap->getToolkit();
        $this->commonDbCluster = $this->commonToolkit->getService('umi\dbal\cluster\IDbCluster');
    }

    protected function initializeMessageBox()
    {
        $this->messageBox = new MockMessageBox();
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

        $that = $this;

        Test::double(
            'umicms\hmvc\component\BaseCmsController',
            [
                'sendMail' => function ($subject, $body, $contentType, $files, $to, $from, $charset) use ($that) {
                    $that->messageBox->push($to, $subject, $body);
                }
            ]
        );

        Test::double(
            'umi\orm\persister\ObjectPersister',
             [
                 'startTransaction' => false,
                 'commitTransaction' => false,
                 'rollbackTransaction' => false
             ]
        );
    }

}
 