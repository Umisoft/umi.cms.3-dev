<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\installer;

use Exception;
use GuzzleHttp;
use PDO;
use PDOException;
use RuntimeException;

/**
 * Инсталятор.
 */
class Installer
{
    /**
     * Доступные типы проектов.
     * @var array $typeProject
     */
    protected $typeProject = [
        'twig', 'php', 'xslt'
    ];

    /**
     * Месторасположения конфига.
     * @var string $config
     */
    private $config;
    /**
     * @var string $updateLink сервер обновлений
     */
    private $updateLink = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91cGRhdGVzZXJ2ZXIzLw';

    /**
     * @return string возвращает ссылку до сервера обновлений
     */
    public function getUpdateLink()
    {
        return base64_decode($this->updateLink);
    }

    /**
     * Конструктор.
     * @param string $config
     */
    public function __construct($config = './config')
    {
        $this->config = $config;
    }

    /**
     * Возвращает доступные типы проектов.
     * @return array
     */
    public function getTypeProject()
    {
        return $this->typeProject;
    }

    /**
     * Скачивает файл
     * @param string $fromUrl удалённый файл
     * @param string $toFile локальный файл
     * @return bool
     */
    public function copyRemote($fromUrl, $toFile) {
        try {
            $client = new GuzzleHttp\Client();
            $response = $client->get($fromUrl)
                ->getBody();

            file_put_contents($toFile, $response);
            return true;
        } catch (Exception $e) {
            // Log the error or something
            return false;
        }
    }

    /**
     * Проверяет соединение с БД.
     * @param string $dbName имя БД
     * @param string $host хост
     * @param string $login логин
     * @param string $password пароль
     * @return bool
     */
    public function checkConnectionDb($dbName, $host, $login, $password)
    {
        try {
            $dsn = 'mysql:dbname=' . $dbName .';host=' . $host;

            @new PDO($dsn, $login, $password);
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * Сохраняет конфиг.
     * @param array $param добавляемый параметр
     */
    public function saveConfig(array $param)
    {
        file_put_contents($this->config, serialize($param));
    }

    /**
     * Возвращает конфиг.
     * @return mixed
     * @throws \RuntimeException
     */
    public function getConfig()
    {
        if (file_exists($this->config)) {
            return unserialize(file_get_contents($this->config));
        } else {
            throw new RuntimeException('File does not exist');
        }
    }

    /**
     * Проверка лицензионного ключа.
     * @param string $licenseKey лицензионный ключ
     * @return bool
     */
    public function checkLicenseKey($licenseKey)
    {
        $source = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91ZGF0YTovL2N1c3RvbS9wcmltYXJ5Q2hlY2tDb2RlLw==';

        $params = array(
            'ip' => $_SERVER['SERVER_ADDR'],
            'domain' => $this->getHostDomain(),
            'keycode' => $licenseKey
        );

        $result = \GuzzleHttp\get(
            base64_decode($source) . base64_encode(serialize($params)).'/'
        )->xml();

        if (isset($result->result)) {
            return true;
        }

        return false;
    }

    /**
     * Получение триального ключа.
     * @param string $email
     * @param string $fname
     * @param string $lname
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     */
    public function getTrialLicense($email, $fname = null, $lname = null)
    {
        $source = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91ZGF0YS9jdXN0b20vZ2VuZXJhdGVMaWNlbnNlR2F0ZU5ldy9EQ0tPSk01TlBMLw==';

        $params = array(
            'email' => rawurlencode($email),
            'fname' => rawurlencode($fname),
            'lname' => rawurlencode($lname),
            'domain' => rawurlencode($_SERVER['HTTP_HOST']),
            'ip' => rawurlencode($_SERVER['SERVER_ADDR'])
        );

        $result = \GuzzleHttp\get(
            base64_decode($source) . implode('/', $params)
        )->xml();

        if (isset($result->keycode)) {
            return $result;
        }

        throw new RuntimeException('Ошибка генерации лицензионного ключа.');
    }

    /**
     * Читает конфиг и заменяет в нём placeholders на значения.
     * @param string $pathTemplateConfig путь до шаблона конфига
     * @param string $pathSaveConfig месторасположение созданного конфига
     * @param array $search искомое значение
     * @param array $replace значение замены
     * @return string
     */
    public function createConfig($pathTemplateConfig, $pathSaveConfig, array $search, array $replace)
    {
        return file_put_contents($pathSaveConfig, str_replace($search, $replace, file_get_contents($pathTemplateConfig)));
    }

    /**
     * Возвращает текущий домен.
     * @return string
     */
    public function getHostDomain()
    {
        $hostDomain = $_SERVER['HTTP_HOST'];
        if (mb_strrpos($hostDomain, 'www.') === 0) {
            $hostDomain = mb_substr($hostDomain, 4);
        }
        return $hostDomain;
    }
}
 