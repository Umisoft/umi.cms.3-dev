<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie as DomCookie;
use Symfony\Component\BrowserKit\Request as DomRequest;
use Symfony\Component\BrowserKit\Response as DomResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use umi\http\Request;
use umi\http\Response;
use umicms\project\Bootstrap;

/**
 * Connector for emulate request-response process to UMI.CMS
 */
class UmiConnector extends Client
{
    /**
     * @var callable $toolkitInitializer
     */
    protected $toolkitInitializer;

    /**
     * @var MockMessageBox
     */
    private $messageBox;

    /**
     * {@inheritdoc}
     * @param Request $request
     * @return Response
     */
    public function doRequest($request)
    {
        if (0 === strpos($request->getRequestUri(), '/messages')) {
            return $this->getMessageResponse($request);
        }

        return $this->getProjectResponse($request);
    }

    /**
     * Set toolkit initializer for any request.
     * @param callable $initializer
     */
    public function setToolkitInitializer(callable $initializer)
    {
        $this->toolkitInitializer = $initializer;
    }

    /**
     * Устанавливает MessageBox
     * @param MockMessageBox $messageBox
     */
    public function setMessageBox(MockMessageBox $messageBox)
    {
        $this->messageBox = $messageBox;
    }

    /**
     * Возвращает MessageBox
     *
     * @throws \LogicException если не был установлен
     *
     * @return MockMessageBox
     */
    protected function getMessageBox()
    {
        if (empty($this->messageBox)) {
            throw new \LogicException('Please set up message box with UmiConnector::setMessageBox');
        }
        return $this->messageBox;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterRequest(DomRequest $request)
    {
        $httpRequest = Request::create(
            $request->getUri(),
            $request->getMethod(),
            $request->getParameters(),
            $request->getCookies(),
            $request->getFiles(),
            $request->getServer(),
            $request->getContent()
        );

        $httpRequest->cookies->add([Bootstrap::SESSION_COOKIE_NAME => 'mockSessionCookie']);

        foreach ($this->filterFiles($httpRequest->files->all()) as $key => $value) {
            $httpRequest->files->set($key, $value);
        }

        return $httpRequest;
    }

    /**
     * {@inheritdoc}
     * @param Response $response The origin response to filter
     */
    protected function filterResponse($response)
    {
        $headers = $response->headers->all();
        if ($response->headers->getCookies()) {
            $cookies = array();
            /**
             * @var Cookie $cookie
             */
            foreach ($response->headers->getCookies() as $cookie) {
                $cookies[] = new DomCookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
            }
            $headers['Set-Cookie'] = $cookies;
        }

        // this is needed to support StreamedResponse
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        return new DomResponse($content, $response->getStatusCode(), $headers);
    }

    /**
     * Filters an array of files.
     *
     * This method created test instances of UploadedFile so that the move()
     * method can be called on those instances.
     *
     * If the size of a file is greater than the allowed size (from php.ini) then
     * an invalid UploadedFile is returned with an error set to UPLOAD_ERR_INI_SIZE.
     *
     * @see Symfony\Component\HttpFoundation\File\UploadedFile
     *
     * @param array $files An array of files
     *
     * @return array An array with all uploaded files marked as already moved
     */
    protected function filterFiles(array $files)
    {
        $filtered = array();
        foreach ($files as $key => $value) {
            if (is_array($value)) {
                $filtered[$key] = $this->filterFiles($value);
            } elseif ($value instanceof UploadedFile) {
                if ($value->isValid() && $value->getSize() > UploadedFile::getMaxFilesize()) {
                    $filtered[$key] = new UploadedFile(
                        '',
                        $value->getClientOriginalName(),
                        $value->getClientMimeType(),
                        0,
                        UPLOAD_ERR_INI_SIZE,
                        true
                    );
                } else {
                    $filtered[$key] = new UploadedFile(
                        $value->getPathname(),
                        $value->getClientOriginalName(),
                        $value->getClientMimeType(),
                        $value->getClientSize(),
                        $value->getError(),
                        true
                    );
                }
            }
        }

        return $filtered;
    }

    /**
     * Возвращает письмо из MessageBox
     * @param Request $request
     * @return Response
     */
    protected function getMessageResponse(Request $request)
    {
        $email = $request->query->get('email');
        $subject = $request->query->get('subject');

        try {
            $content = $this->getMessageBox()->read($email, $subject);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        if (false === stripos($content, '<html>')) {
            return '<html>' . $content .'</html>';
        }

        return new Response($content, Response::HTTP_OK);
    }

    /**
     * Возвращает ответ проекта
     * @param Request $request
     * @return Response
     */
    protected function getProjectResponse(Request $request)
    {
        $bootstrap = new Bootstrap($request);
        if ($bootstrap->init()) {
            if ($this->toolkitInitializer) {
                $initializer = $this->toolkitInitializer;
                $initializer($bootstrap->getToolkit());
            }
            $bootstrap->dispatch();
        }

        return $bootstrap->getResponse();
    }
}
 