<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\files\admin\manager\controller;

use elFinder;
use elFinderConnector;
use umicms\hmvc\component\BaseCmsController;

/**
 * Контроллер файлового менеджера.
 */
class ConnectorController extends BaseCmsController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $opts = [
            'roots' => [
                [
                    'driver'        => 'LocalFileSystem',
                    'path'          => PUBLIC_DIR . '/files',
                    'URL'           => '/files',
                    'accessControl' => [$this, 'accessControl'],
                    'uploadDeny' => ['text/x-php'],
                    'uploadOverwrite' => false
                ]
            ]
        ];

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

    /**
     * Проверяет доступность файлов и директорий
     * @param $attr
     * @param $path
     * @param $data
     * @param $volume
     * @return bool|null
     */
    public function accessControl($attr, $path, $data, $volume)
    {
        return strpos(basename($path), '.') === 0 ?
            !($attr == 'read' || $attr == 'write') : null;
    }
}
 