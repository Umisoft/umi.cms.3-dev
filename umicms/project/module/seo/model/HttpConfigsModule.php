<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\model;

use umicms\module\BaseModule;
use umicms\project\Environment;
use umicms\project\module\seo\model\object\Redirect;

class HttpConfigsModule extends BaseModule
{
    const START_MARKER = '# Start UMI.CMS rewrite settings';
    const END_MARKER = '# End UMI.CMS rewrite settings';

    /**
     * Хранит префикс проекта
     * @var string
     */
    private $projectPrefix;

    public function refresh($projectPrefix)
    {
        $this->projectPrefix = $projectPrefix;
        $trimmedPrefix = ltrim($projectPrefix, '/');
        $apache = $nginx = [];
        /** @var Redirect $redirect */
        foreach ($this->getCollection('redirects')->select()->result() as $redirect) {
            $apache[] = "RewriteRule {$trimmedPrefix}/{$redirect->sourcePattern} {$trimmedPrefix}/{$redirect->targetPattern} [R=301,L]";
            $nginx[] = "rewrite {$this->projectPrefix}/{$redirect->sourcePattern} {$this->projectPrefix}/{$redirect->targetPattern} permanent;";
        }
        $this->updateConfig($this->getFilePath(Environment::$directoryPublic, '.htaccess'), $apache, '    ');
        $this->updateConfig($this->getFilePath(Environment::$directoryRoot, 'umi.nginx.conf'), $nginx, '  ');
    }

    private function getFilePath($dir, $name)
    {
        return rtrim($dir, '/') . '/' . $name;
    }

    /**
     * Обновляет конфиг
     * @param string $path
     * @param array $rewrites
     * @param string $padding пробелы для выравнивания в результирующем файле
     */
    private function updateConfig($path, array $rewrites, $padding = '')
    {
        $currentConfig = file_get_contents($path);
        if (!preg_match('/' . self::START_MARKER . '(.*)' . self::END_MARKER . '/s', $currentConfig, $matches)) {
            throw new \RuntimeException('Can not find markers for rewrite config in ' . $path);
        }

        $start = "# Start rewrite of {$this->projectPrefix}";
        $end =   "# End rewrite of {$this->projectPrefix}";

        $rewriteSection = trim($matches[1]);
        if (false !== strpos($rewriteSection, $start)) {
            $rewriteSection = trim(preg_replace(
                '/' . preg_quote($start, '/') . '.*' . preg_quote($end, '/')  . '/s',
                '',
                $rewriteSection
            ));
        }
        array_unshift($rewrites, $padding . $start);
        $rewrites[] = $end;
        $rewriteSection .= implode("\n" . $padding, $rewrites);
        $newConfig = preg_replace(
            '/(' . self::START_MARKER . ').*(' . self::END_MARKER . ')/s',
            "$1\n" . $rewriteSection . "\n" . $padding . "$2",
            $currentConfig
        );
        file_put_contents($path, $newConfig, LOCK_EX);
    }

}