<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umi\orm\collection\ICollectionManager;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\dump\ICmsObjectExporter;
use umicms\orm\object\ICmsObject;

/**
 * Создает дамп данных проекта.
 */
class CreateDumpCommand extends BaseProjectCommand
{
    /**
     * @var array $ignoreCollections имена коллекций, которые будут проигнорированы в дампе.
     */
    public $ignoreCollections = ['serviceBackup', 'searchIndex'];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('create-dump')
            ->setDescription('Create dump for project data.');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $bootstrap = $this->dispatchToProject($input, $output);
        $toolkit = $bootstrap->getToolkit();

        $dumpDirectory = $bootstrap->getProjectDumpDirectory();
        $output->writeln('<info>Dump directory: "' . $dumpDirectory . '"</info>');

        if (!is_dir($dumpDirectory)) {
            mkdir($dumpDirectory, 0777, true);
        }

        /**
         * @var ICollectionManager $collectionManager
         */
        $collectionManager = $toolkit->getService('umi\orm\collection\ICollectionManager');
        /**
         * @var ICmsObjectExporter $objectExporter
         */
        $objectExporter =  $toolkit->getService('umicms\orm\dump\ICmsObjectExporter');


        $progress = $this->startProgressBar($output, count($collectionManager->getList()));

        foreach ($collectionManager->getList() as $collectionName)
        {
            $progress->setMessage('Create dump for "' . $collectionName . '".');
            $progress->advance();

            if (in_array($collectionName, $this->ignoreCollections)) {
                $progress->setMessage('Collection "' . $collectionName . '" in ignore list.');
                continue;
            }

            /**
             * @var ICmsCollection $collection
             */
            $collection = $collectionManager->getCollection($collectionName);
            $dump = $objectExporter->getDump(
                $collection->getInternalSelector()->orderBy(ICmsObject::FIELD_GUID)
            );

            if (count($dump)) {
                $progress->setMessage('Writing dump for "' . $collectionName . '".');
                $contents = $this->getDumpFile($collectionName, $dump);
                file_put_contents($dumpDirectory . '/' . $collectionName . '.dump.php', $contents);
            } else {
                $progress->setMessage('Collection "' . $collectionName . '" is empty.');
            }
        }

        $progress->setMessage('Complete.');
        $progress->finish();

        $output->writeln('');
        $output->writeln('<process>Complete.</process>');
    }

    /**
     * Возвращает содержимое файла дампа.
     * @param string $collectionName
     * @param array $data
     * @return string
     */
    private function getDumpFile($collectionName, array $data)
    {
        $source =  var_export($data, true);

        return <<<FILE
<?php
/**
 * Collection "{$collectionName}" dump.
 */
return $source;
FILE;

    }

}
