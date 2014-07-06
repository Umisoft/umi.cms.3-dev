<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\dump;

use umicms\orm\selector\CmsSelector;
use umicms\serialization\exception\RequiredDependencyException;

/**
 * Class TCmsObjectDumpAware
 */
trait TCmsObjectDumpAware
{
    /**
     * @var ICmsObjectImporter $importer
     */
    private $traitObjectImporter;
    /**
     * @var ICmsObjectExporter $importer
     */
    private $traitObjectExporter;

    /**
     * @see ICmsObjectDumpAware::setObjectImporter
     */
    public function setObjectImporter(ICmsObjectImporter $importer)
    {
        $this->traitObjectImporter = $importer;
    }

    /**
     * @see ICmsObjectDumpAware::setObjectExporter
     */
    public function setObjectExporter(ICmsObjectExporter $exporter)
    {
        $this->traitObjectExporter = $exporter;
    }

    /**
     * Получить дамп объектов.
     * @param CmsSelector $selector
     * @throws \umicms\serialization\exception\RequiredDependencyException
     * @return array
     */
    protected function getObjectsDump(CmsSelector $selector)
    {
        if (!$this->traitObjectExporter) {
            throw new RequiredDependencyException(sprintf(
                'Object exporter is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitObjectExporter->getDump($selector);
    }

    /**
     * Загрузить объекты из дампа в память.
     * @param array $dump
     * @throws RequiredDependencyException
     * @return array
     */
    protected function loadObjectsFromDump(array $dump)
    {
        if (!$this->traitObjectImporter) {
            throw new RequiredDependencyException(sprintf(
                'Object exporter is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitObjectImporter->loadDump($dump);
    }
}
 