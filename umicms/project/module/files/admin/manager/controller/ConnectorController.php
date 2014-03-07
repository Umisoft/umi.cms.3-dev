<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\files\admin\manager\controller;

use elFinder;
use elFinderConnector;
use umicms\hmvc\controller\BaseController;

/**
 * Контроллер файлового менеджера.
 */
class ConnectorController extends BaseController
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
                    'accessControl' => [$this, 'accessControl']
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
        return strpos(basename($path), '.') === 0
            ? !($attr == 'read' || $attr == 'write')
            : null;
    }
}
 