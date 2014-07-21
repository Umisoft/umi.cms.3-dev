<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\command;

use umicms\install\installer\Installer;
use umicms\install\exception\RuntimeException;
use umi\orm\collection\ICollectionManager;
use umi\orm\persister\IObjectPersister;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\object\Supervisor;

class CreateSupervisor implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
     */
    private $installer;
    /**
     * @var string $accessData новые данные доступа
     */
    private $siteAccess;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
        $this->siteAccess = $param;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        require(CMS_CORE_PHP);

        $bootstrap = new Bootstrap();
        $bootstrap->dispatchProject();

        $toolkit = $bootstrap->getToolkit();

        /**
         * @var IObjectPersister $objectPersister
         */
        $objectPersister = $toolkit->getService('umi\orm\persister\IObjectPersister');
        /**
         * @var ICollectionManager $collectionManager
         */
        $collectionManager = $toolkit->getService('umi\orm\collection\ICollectionManager');

        $userCollection = $collectionManager->getCollection('user');

        $config = $this->installer->getConfig();

        if (isset($this->siteAccess['login'])) {
            $config['siteAccess']['login'] = $this->siteAccess['login'];
        }
        if (isset($this->siteAccess['email'])) {
            $config['siteAccess']['email'] = $this->siteAccess['email'];
        }

        if (
            !isset($config['siteAccess']['login']) ||
            !isset($config['siteAccess']['email']) ||
            !isset($config['siteAccess']['password'])
        ) {
            throw new RuntimeException('Недостаточно данных для создания супервайзера');
        }

        /** @var Supervisor $sv */
        $sv = $userCollection->get('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');
        $sv->setValue('login', $config['siteAccess']['login']);
        $sv->setValue('email', $config['siteAccess']['email']);
        $sv->setPassword($config['siteAccess']['password']);

        if ($invalidObjects = $objectPersister->getInvalidObjects()) {
            $errorString = '';
            foreach ($invalidObjects as $object) {
                foreach ($object->getValidationErrors() as $propertyName => $errors) {
                    $errorString .= $propertyName . ': ';
                    foreach ($errors as $error) {
                        $errorString .= $error . ', ';
                    }
                }
            }

            $overlay = <<<EOF
<label>Логин<br/>
    <input name="sv_login" type="text" tabindex="1" class="js-handler-changeAuthData-login"/>
</label><br/>
<label>Email<br/>
    <input name="sv_email" type="text" tabindex="2" class="js-handler-changeAuthData-email"/>
</label><br/>
<input type="submit" class="next_step_submit marginr_px next js-handler-changeAuthData" value="Изменить и продолжить"/>
EOF;

            throw new RuntimeException('Произошла ошибка при создании супервайзера. <br/>' . $errorString, $overlay);
        } else {
            $objectPersister->commit();
        }

        if (!$sv->valid()) {
            throw new RuntimeException('Невалидный юзер.');
        }

        rename(INSTALL_ROOT_DIR . '/.htaccess.dist', INSTALL_ROOT_DIR . '/.htaccess');

        return true;
    }
}
 