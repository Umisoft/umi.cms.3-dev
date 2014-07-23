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

use umicms\install\exception\RuntimeException;
use umicms\install\installer\Installer;
use Symfony\Component\Finder\Finder;
use umi\orm\persister\IObjectPersister;
use umicms\orm\dump\ICmsObjectImporter;
use umicms\orm\object\ICmsPage;
use umicms\project\Bootstrap;
use umicms\project\module\search\model\SearchModule;

/**
 * Востанавливает дамп БД.
 */
class LoadDump implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
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
         * @var ICmsObjectImporter $objectImporter
         */
        $objectImporter =  $toolkit->getService('umicms\orm\dump\ICmsObjectImporter');
        /**
         * @var IObjectPersister $objectPersister
         */
        $objectPersister =  $toolkit->getService('umi\orm\persister\IObjectPersister');
        /**
         * @var SearchModule $searchModule
         */
        $searchModule = $toolkit->getService('umicms\module\IModule', SearchModule::className());

        $dumpDirectory = $bootstrap->getProjectDumpDirectory();

        $finder = new Finder();
        $finder->files()
            ->name('*.php')
            ->in($dumpDirectory);

        foreach ($finder as $dumpFile)
        {
            $objectImporter->loadDump(require $dumpFile);
        }

        // todo: не работает индекс поиска

        /**
         * @var ICmsPage $object
         */
        foreach ($objectPersister->getNewObjects() as $object) {
            if ($object instanceof ICmsPage) {
                $searchModule->getSearchIndexApi()->buildIndexForObject($object);
            }
        }

        foreach ($objectPersister->getModifiedObjects() as $object) {
            if ($object instanceof ICmsPage) {
                $searchModule->getSearchIndexApi()->buildIndexForObject($object);
            }
        }

        if ($invalidObjects = $objectPersister->getInvalidObjects()) {
            // todo: обработка ошибок валидации
            throw new RuntimeException('Произошла ошибка валидации объектов.');
        } else {
            $objectPersister->commit();
        }

        return true;
    }
}
 