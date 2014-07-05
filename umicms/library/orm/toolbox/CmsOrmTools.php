<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\toolbox;

use umi\orm\toolbox\ORMTools;
use umicms\orm\dump\ICmsObjectDumpAware;
use umicms\orm\dump\ICmsObjectExporter;
use umicms\orm\dump\ICmsObjectImporter;

/**
 * {@inheritdoc}
 */
class CmsOrmTools extends ORMTools
{
    /**
     * @var string $objectExporterClass имя класса для экспорта объектов
     */
    public $objectExporterClass = 'umicms\orm\dump\CmsObjectExporter';
    /**
     * @var string $objectImporterClass  имя класса для импорта объектов
     */
    public $objectImporterClass = 'umicms\orm\dump\CmsObjectImporter';

    /**
     * {@inheritdoc}
     */
    public function getService($serviceInterfaceName, $concreteClassName)
    {
        if ($serviceInterfaceName == 'umicms\orm\dump\ICmsObjectExporter') {
            return $this->getCmsObjectExporter();
        }
        if ($serviceInterfaceName == 'umicms\orm\dump\ICmsObjectImporter') {
            return $this->getCmsObjectImporter();
        }

        return parent::getService($serviceInterfaceName, $concreteClassName);
    }

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof ICmsObjectDumpAware) {
            $object->setObjectExporter($this->getCmsObjectExporter());
            $object->setObjectImporter($this->getCmsObjectImporter());
        }


        parent::injectDependencies($object);
    }

    /**
     * Создает и возвращает экспортер объектов в дамп.
     * @return ICmsObjectExporter
     */
    protected function getCmsObjectExporter()
    {
        return $this->getPrototype(
            $this->objectExporterClass,
            ['umicms\orm\dump\ICmsObjectExporter']
        )
            ->createSingleInstance();
    }

    /**
     * Создает и возвращает импортер объектов из дампа.
     * @return ICmsObjectImporter
     */
    protected function getCmsObjectImporter()
    {
        return $this->getPrototype(
            $this->objectImporterClass,
            ['umicms\orm\dump\ICmsObjectImporter']
        )
            ->createSingleInstance();
    }

}
 