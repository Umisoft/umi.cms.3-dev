<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umi\orm\collection\ICollectionManager;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\dump\ICmsObjectExporter;
use umicms\orm\object\ICmsObject;

/**
 * Создает дамп данных проекта.
 */
class ProjectCreateDumpCommand extends BaseProjectCommand
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
            ->setDescription('Создает дамп данных проекта.')
            ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'URI проекта'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $bootstrap = $this->dispatchToProject($input, $output);
        $toolkit = $bootstrap->getToolkit();

        $dumpDirectory = $bootstrap->getProjectDirectory() . '/dump';
        if (!is_dir($dumpDirectory)) {
            mkdir($dumpDirectory);
        }

        $output->writeln('<info>Директория для дампа данных "' . $dumpDirectory . '"</info>');

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
            $progress->setMessage('Выгрузка данных коллекции "' . $collectionName . '"');
            $progress->advance();

            if (in_array($collectionName, $this->ignoreCollections)) {
                $progress->setMessage('Коллекция "' . $collectionName . '" проигнорирована.');
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
                $progress->setMessage('Запись данных коллекции "' . $collectionName . '"');
                $contents = $this->getDumpFile($collectionName, $dump);
                file_put_contents($dumpDirectory . '/' . $collectionName . '.dump.php', $contents);
            } else {
                $progress->setMessage('Коллекция "' . $collectionName . '" пуста');
            }
        }

        $progress->setMessage('Complete.');
        $progress->finish();

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
