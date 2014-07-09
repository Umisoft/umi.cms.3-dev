<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use umi\http\Request;
use umi\orm\collection\ICollectionManager;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\dump\ICmsObjectExporter;
use umicms\project\Bootstrap;

/**
 * Создает дамп данных проекта.
 */
class ProjectDumpCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dump')
            ->setDescription('Создает дамп данных проекта.')
            ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'URI проекта'
            )
            ->addArgument(
                'output',
                InputArgument::OPTIONAL,
                'Полый путь пакета, который будет создан.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectUri = trim($input->getArgument('uri'));

        $style = new OutputFormatterStyle('blue', null, array('bold'));
        $output->getFormatter()->setStyle('process', $style);

        $bootstrap = new Bootstrap();
        $toolkit = $bootstrap->getToolkit();

        /**
         * @var Request $request
         */
        $request = Request::create($projectUri);

        $output->writeln('<process>Диспетчеризация проекта "' . $projectUri . '"</process>');

        $bootstrap->routeProject($request);
        $exportDirectory = $bootstrap->getProjectDirectory() . '/dump';
        if (!is_dir($exportDirectory)) {
            mkdir($exportDirectory);
        }

        $output->writeln('<process>Директория выгрузки "' . $exportDirectory . '"</process>');

        /**
         * @var ICollectionManager $collectionManager
         */
        $collectionManager = $toolkit->getService('umi\orm\collection\ICollectionManager');
        /**
         * @var ICmsObjectExporter $objectExporter
         */
        $objectExporter =  $toolkit->getService('umicms\orm\dump\ICmsObjectExporter');


        $progress = new ProgressBar($output, count($collectionManager->getList()));

        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
        $progress->start();

        foreach ($collectionManager->getList() as $collectionName)
        {
            $progress->setMessage('Выгрузка данных коллекции "' . $collectionName . '"');
            $progress->advance();
            /**
             * @var ICmsCollection $collection
             */
            $collection = $collectionManager->getCollection($collectionName);
            $dump = $objectExporter->getDump($collection->getInternalSelector());

            $progress->setMessage('Запись данных "' . $collectionName . '"');
            $contents = $this->getDumpFile($collectionName, $dump);
            file_put_contents($exportDirectory . '/' . $collectionName . '.dump.php', $contents);
        }

        $progress->setMessage('Complete.');
        $progress->finish();

        $output->writeln('<process>Complete.</process>');
    }

    /**
     * Возвращает содержимое файла дампа.
     * @param string $collectionName
     * @param array $dump
     * @return string
     */
    private function getDumpFile($collectionName, array $dump)
    {
        $source =  var_export($dump, true);

        return <<<FILE
<?php
/**
 * Collection "{$collectionName}" dump.
 */
return $source;
FILE;

    }

}
