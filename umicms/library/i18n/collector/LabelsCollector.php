<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\i18n\collector;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use umi\i18n\exception\RuntimeException;

/**
 * Коллектор лейблов.
 */
class LabelsCollector
{
    /**
     * Возвращет лейблы, используемые в файлах заданной директории
     * @param string $initialDir путь к директории
     * @throws RuntimeException если заданный путь не существует
     * @return array
     */
    public function getLabels($initialDir)
    {
        if (!is_dir($initialDir)) {
            throw new RuntimeException(sprintf('Cannot get labels from %s. Wrong path given.', $initialDir));
        }

        $labels = [];

        $directoryIterator = new RecursiveDirectoryIterator($initialDir, FilesystemIterator::SKIP_DOTS);
        $filesIterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($filesIterator as $filename => $info) {
            $tokens = token_get_all(file_get_contents($filename));
            $i18nSource = new LabelsSource($tokens);
            $result = $i18nSource->getLabels();
            if ($result) {
                list($namespace, $sourceLabels) = $result;
                if ($sourceLabels) {
                    if (isset($labels[$namespace])) {
                        $sourceLabels = array_merge($labels[$namespace], $sourceLabels);
                    }
                    $sourceLabels = array_unique($sourceLabels);
                    sort($sourceLabels);
                    $labels[$namespace] = $sourceLabels;
                }
            }
        }

        ksort($labels);

        return $labels;
    }
}