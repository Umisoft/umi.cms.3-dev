<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use umi\orm\persister\IObjectPersister;
use umicms\orm\dump\ICmsObjectImporter;
use umicms\orm\object\ICmsObject;

/**
 * Загружает данные проекта из дампа.
 */
class ProjectLoadDumpCommand extends BaseProjectCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('load-dump')
            ->setDescription('Загружает дамп данных проекта.')
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
        /**
         * @var ICmsObjectImporter $objectImporter
         */
        $objectImporter =  $toolkit->getService('umicms\orm\dump\ICmsObjectImporter');

        $output->writeln('<process>Загружаем дамп в память.</process>');

        $finder = new Finder();
        $finder->files()
            ->name('*.php')
            ->ignoreVCS(true)
            ->in($dumpDirectory);

        $progress = $this->startProgressBar($output, count($finder));

        foreach ($finder as $dumpFile)
        {
            $progress->setMessage('Загрузка файла "' . $dumpFile . '"');
            $progress->advance();

            $objectImporter->loadDump(require $dumpFile);
        }

        $progress->setMessage('Complete.');
        $progress->finish();

        /**
         * @var IObjectPersister $objectPersister
         */
        $objectPersister =  $toolkit->getService('umi\orm\persister\IObjectPersister');

        $output->writeln('');
        $output->writeln('<info>Загружено объектов на создание: ' . count($objectPersister->getNewObjects()) . ' </info>');
        $output->writeln('<info>Загружено объектов на модификацию: ' . count($objectPersister->getModifiedObjects()) . '</info>');

        if ($invalidObjects = $objectPersister->getInvalidObjects()) {
            $output->writeln('<error>Ошибки валидации</error>');

            $table = new Table($output);
            $table->setHeaders(array('Guid', 'Type', 'DisplayName', 'Property', 'Error'));

            /**
             * @var ICmsObject $object
             */
            foreach ($invalidObjects as $object) {
                foreach ($object->getValidationErrors() as $propertyName => $errors) {
                    foreach ($errors as $error) {
                        $table->addRow([$object->guid, $object->getTypePath(), $object->displayName, $propertyName, $error]);
                    }
                }
                $table->addRow(new TableSeparator());
            }

            $table->render();
        } else {
            $output->writeln('<info>Записываем объекты в БД</info>');
            $objectPersister->commit();
            $output->writeln('<process>Complete</process>');
        }



    }



}
