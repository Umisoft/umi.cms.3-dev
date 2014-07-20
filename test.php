<?php
namespace umicms\project\site;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\exception\http\HttpException;
use umi\http\IHttpAware;
use umi\http\Request;
use umi\http\Response;
use umi\http\THttpAware;
use umi\orm\collection\BaseCollection;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umi\toolkit\IToolkitAware;
use umi\toolkit\TToolkitAware;
use umicms\exception\InvalidLicenseException;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\component\site\SiteComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\TCmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\selector\CmsSelector;
use umicms\project\Bootstrap;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

class SiteApplication extends SiteComponent
    implements IHttpAware, IToolkitAware, ISerializationAware, IUrlManagerAware, ISessionAware
{
    use THttpAware;
    use TToolkitAware;
    use TSerializationAware;
    use TUrlManagerAware;
    use TSiteSettingsAware;
    use TSessionAware;

    const SETTING_DEFAULT_PAGE_GUID = 'defaultPage';
    const SETTING_DEFAULT_LAYOUT_GUID = 'defaultLayout';
    const SETTING_DEFAULT_TITLE = 'defaultMetaTitle';
    const SETTING_TITLE_PREFIX = 'metaTitlePrefix';
    const SETTING_DEFAULT_KEYWORDS = 'defaultMetaKeywords';
    const SETTING_DEFAULT_DESCRIPTION = 'defaultMetaDescription';
    const SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE = 'defaultTemplatingEngineType';
    const SETTING_DEFAULT_TEMPLATE_EXTENSION = 'defaultTemplateExtension';
    const SETTING_COMMON_TEMPLATE_DIRECTORY = 'commonTemplateDirectory';
    const SETTING_TEMPLATE_DIRECTORY = 'templateDirectory';
    const SETTING_BROWSER_CACHE_ENABLED = 'browserCacheEnabled';
    const OPTION_SERIALIZERS = 'serializers';
    const DEFAULT_REQUEST_FORMAT = 'html';
    protected $supportedRequestPostfixes = ['json', 'xml'];

    public function __construct(
        $vb068931cc450442b63f5b3d276ea4297,
        $vd6fe1d0be6347b8ef2427fa629c04485,
        array $v93da65a9fd0004d9477aeac024e08e15 = []
    )
    {
        parent::__construct(
            $vb068931cc450442b63f5b3d276ea4297,
            $vd6fe1d0be6347b8ef2427fa629c04485,
            $v93da65a9fd0004d9477aeac024e08e15
        );
        $this->registerSiteSettings();
    }

    public function onDispatchRequest(
        IDispatchContext $v5c18ef72771564b7f43c497dc507aeab,
        Request $v10573b873d2fa5a365d558a45e328e47
    )
    {
        $this->checkLicense($v10573b873d2fa5a365d558a45e328e47);
        $this->registerSelectorInitializer();
        $this->registerSerializers();
        while (!$this->getPageCallStack()
            ->isEmpty()) {
            $this->getPageCallStack()
                ->pop();
        }
        $v8e2dcfd7e7e24b1ca76c1193f645902b = null;
        if (isset($v5c18ef72771564b7f43c497dc507aeab->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT])) {
            $v8e2dcfd7e7e24b1ca76c1193f645902b = $v5c18ef72771564b7f43c497dc507aeab->getRouteParams(
            )[self::MATCH_STRUCTURE_ELEMENT];
            if ($v8e2dcfd7e7e24b1ca76c1193f645902b instanceof ICmsPage) {
                if ($v8e2dcfd7e7e24b1ca76c1193f645902b instanceof CmsHierarchicObject) {
                    foreach ($v8e2dcfd7e7e24b1ca76c1193f645902b->getAncestry() as $vd0e45878043844ffc41aac437e86b602) {
                        $this->pushCurrentPage($vd0e45878043844ffc41aac437e86b602);
                    }
                }
                $this->pushCurrentPage($v8e2dcfd7e7e24b1ca76c1193f645902b);
            }
        }

        return null;
    }

    public function onDispatchResponse(
        IDispatchContext $v5c18ef72771564b7f43c497dc507aeab,
        Response $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe
    )
    {
        $v10573b873d2fa5a365d558a45e328e47 = $v5c18ef72771564b7f43c497dc507aeab->getDispatcher()
            ->getCurrentRequest();
        $vf3b20fb056d0709cad072b0c38880a4b = $v10573b873d2fa5a365d558a45e328e47->getPathInfo();
        $v0f7bdcd1a64fcc5be335f08d687f629f = $v10573b873d2fa5a365d558a45e328e47->getRequestFormat(null);
        if ($v0f7bdcd1a64fcc5be335f08d687f629f) {
            $vf3b20fb056d0709cad072b0c38880a4b = substr(
                $vf3b20fb056d0709cad072b0c38880a4b,
                0,
                -strlen($v0f7bdcd1a64fcc5be335f08d687f629f) - 1
            );
        }
        $v3e1f4d3bc40fd7cc9043af7f2c59f305 = $vf3b20fb056d0709cad072b0c38880a4b === $this->getUrlManager()
                ->getProjectUrl();
        if (!$v3e1f4d3bc40fd7cc9043af7f2c59f305 && $v9f72984c9ca1f5fa65fbe01140a5c873 = $this->processUrlPostfixRedirect(
                $v10573b873d2fa5a365d558a45e328e47
            )
        ) {
            return $v9f72984c9ca1f5fa65fbe01140a5c873;
        }
        if (!$v3e1f4d3bc40fd7cc9043af7f2c59f305 && $v9f72984c9ca1f5fa65fbe01140a5c873 = $this->processDefaultPageRedirect(
                $v0f7bdcd1a64fcc5be335f08d687f629f
            )
        ) {
            return $v9f72984c9ca1f5fa65fbe01140a5c873;
        }
        if (!is_null(
                $v0f7bdcd1a64fcc5be335f08d687f629f
            ) && $v0f7bdcd1a64fcc5be335f08d687f629f !== self::DEFAULT_REQUEST_FORMAT
        ) {
            if ($vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->headers->has('content-type')) {
                throw new HttpException(Response::HTTP_NOT_FOUND, $this->translate(
                    'Cannot serialize response. Headers had been already set.'
                ));
            }
            if ($vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->getIsCompleted()) {
                $v87cd8b8808600624d8c590cfc2e6e94b = ['result' => $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->getContent()];
            } else {
                $v87cd8b8808600624d8c590cfc2e6e94b = ['layout' => $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->getContent()];
            }
            $vb4a88417b3d0170d754c647c30b7216a = $this->serializeResult(
                $v0f7bdcd1a64fcc5be335f08d687f629f,
                $v87cd8b8808600624d8c590cfc2e6e94b
            );
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setContent($vb4a88417b3d0170d754c647c30b7216a);
        } elseif ($this->getSiteBrowserCacheEnabled()) {
            $this->setBrowserCacheHeaders($v10573b873d2fa5a365d558a45e328e47, $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe);
        }

        return $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe;
    }

    protected function postRedirectGet(Request $v10573b873d2fa5a365d558a45e328e47)
    {
        $v278d8b8a9d1ef0cc6d7eaf4f18cd841b = 'prg_' . md5($v10573b873d2fa5a365d558a45e328e47->getRequestUri());
        $v0f7bdcd1a64fcc5be335f08d687f629f = $v10573b873d2fa5a365d558a45e328e47->getRequestFormat(null);
        if ($v10573b873d2fa5a365d558a45e328e47->getMethod() === 'POST' && empty($_FILES) && (is_null(
                    $v0f7bdcd1a64fcc5be335f08d687f629f
                ) || $v0f7bdcd1a64fcc5be335f08d687f629f == self::DEFAULT_REQUEST_FORMAT)
        ) {
            $v42b90196b487c54069097a68fe98ab6f = $v10573b873d2fa5a365d558a45e328e47->request->all();
            $this->setSessionVar($v278d8b8a9d1ef0cc6d7eaf4f18cd841b, $v42b90196b487c54069097a68fe98ab6f);
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe = $this->createHttpResponse();
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->headers->set(
                'Location',
                $v10573b873d2fa5a365d558a45e328e47->getRequestUri()
            );
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setStatusCode(Response::HTTP_FOUND);

            return $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe;
        } elseif ($v10573b873d2fa5a365d558a45e328e47->cookies->has(
                Bootstrap::SESSION_COOKIE_NAME
            ) && $this->hasSessionVar($v278d8b8a9d1ef0cc6d7eaf4f18cd841b)
        ) {
            $v10573b873d2fa5a365d558a45e328e47->server->set('REQUEST_METHOD', 'POST');
            $v10573b873d2fa5a365d558a45e328e47->request->replace(
                $this->getSessionVar($v278d8b8a9d1ef0cc6d7eaf4f18cd841b)
            );
            $this->removeSessionVar($v278d8b8a9d1ef0cc6d7eaf4f18cd841b);

            return null;
        }

        return null;
    }

    protected function getSiteSettings()
    {
        return $this->getSettings();
    }

    protected function setBrowserCacheHeaders(
        Request $v10573b873d2fa5a365d558a45e328e47,
        Response $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe
    )
    {
        $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setETag(md5($vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->getContent()));
        $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setPublic();
        $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->isNotModified($v10573b873d2fa5a365d558a45e328e47);
    }

    protected function serializeResult($v1ddcb92ade31c8fbd370001f9b29a7d9, $v87cd8b8808600624d8c590cfc2e6e94b)
    {
        $v84f6fb7cd5cd53b5679489a29396448f = $this->getSerializer(
            $v1ddcb92ade31c8fbd370001f9b29a7d9,
            $v87cd8b8808600624d8c590cfc2e6e94b
        );
        $v84f6fb7cd5cd53b5679489a29396448f->init();
        $v84f6fb7cd5cd53b5679489a29396448f($v87cd8b8808600624d8c590cfc2e6e94b);

        return $v84f6fb7cd5cd53b5679489a29396448f->output();
    }

    protected function registerSerializers()
    {
        if (isset($this->options[self::OPTION_SERIALIZERS])) {
            $v99d76b84c112c3da5f1394b7b68ea41e = $this->configToArray($this->options[self::OPTION_SERIALIZERS], true);
            $v1a9365a84cf77e7260d607aa1210c14e = $this->getToolkit()
                ->getService('umicms\serialization\ISerializerFactory');
            $v1a9365a84cf77e7260d607aa1210c14e->registerSerializers($v99d76b84c112c3da5f1394b7b68ea41e);
        }
    }

    protected function processUrlPostfixRedirect(Request $v10573b873d2fa5a365d558a45e328e47)
    {
        $v11e868acb4d0d3552993647c02ffc75f = $this->getUrlManager()
            ->getSiteUrlPostfix();
        if ($v11e868acb4d0d3552993647c02ffc75f && is_null($v10573b873d2fa5a365d558a45e328e47->getRequestFormat(null))) {
            $v328595edab1f74adef109fa1e40bfb8b = $v10573b873d2fa5a365d558a45e328e47->getBaseUrl(
                ) . $v10573b873d2fa5a365d558a45e328e47->getPathInfo() . '.' . $v11e868acb4d0d3552993647c02ffc75f;
            if ($vbe571b25caf2bbed46f6e47182670bf7 = $v10573b873d2fa5a365d558a45e328e47->getQueryString()) {
                $v328595edab1f74adef109fa1e40bfb8b .= '?' . $vbe571b25caf2bbed46f6e47182670bf7;
            }
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe = $this->createHttpResponse();
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->headers->set('Location', $v328595edab1f74adef109fa1e40bfb8b);
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setIsCompleted();

            return $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe;
        }

        return null;
    }

    protected function processDefaultPageRedirect($v4ec1b477cd0232b832c1899905ec51a4 = null)
    {
        if ($this->hasCurrentPage() && $this->getCurrentPage()
                ->getGUID() === $this->getSiteDefaultPageGuid()
        ) {
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe = $this->createHttpResponse();
            $vd5189de027922f81005951e6efe0efd5 = $this->getUrlManager()
                ->getProjectUrl();
            if ($v4ec1b477cd0232b832c1899905ec51a4 && $v4ec1b477cd0232b832c1899905ec51a4 != $this->getUrlManager()
                    ->getSiteUrlPostfix()
            ) {
                $vd5189de027922f81005951e6efe0efd5 .= '.' . $v4ec1b477cd0232b832c1899905ec51a4;
            }
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->headers->set('Location', $vd5189de027922f81005951e6efe0efd5);
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
            $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe->setIsCompleted();

            return $vd1fc8eaf36937be0c3ba8cfe0a2c1bfe;
        }

        return null;
    }

    protected function registerSiteSettings()
    {
        $this->setSiteSettings($this->getSettings());
        $this->getToolkit()
            ->registerAwareInterface(
            'umicms\project\site\config\ISiteSettingsAware',
            function ($va8cfde6331bd59eb2ac96f8911c4b666) {
                if ($va8cfde6331bd59eb2ac96f8911c4b666 instanceof ISiteSettingsAware) {
                    $va8cfde6331bd59eb2ac96f8911c4b666->setSiteSettings($this->getSettings());
                }
            }
        );
    }

    protected function registerSelectorInitializer()
    {
        BaseCollection::setSelectorInitializer(
            function (CmsSelector $v5b3c32009797feb79096d52e56a56b82) {
                $vdb6d9b451b818ccc9a449383f2f0c450 = $v5b3c32009797feb79096d52e56a56b82->getCollection();
                if ($vdb6d9b451b818ccc9a449383f2f0c450 instanceof IRecyclableCollection) {
                    $v5b3c32009797feb79096d52e56a56b82->where(IRecyclableObject::FIELD_TRASHED)
                        ->notEquals(true);
                }
                if ($vdb6d9b451b818ccc9a449383f2f0c450 instanceof IActiveAccessibleCollection) {
                    $v5b3c32009797feb79096d52e56a56b82->where(IActiveAccessibleObject::FIELD_ACTIVE)
                        ->equals(true);
                }
            }
        );
    }

    private function checkLicense(Request $v10573b873d2fa5a365d558a45e328e47)
    {
        $v2b3291e2ad6ca2773e393d0f77003dda = $this->getSiteSettings()
            ->get('domainKey');
        $v9d6e24ec78d309695833f4c9f8310d7a = $this->getDefaultDomain();
        if (empty($v2b3291e2ad6ca2773e393d0f77003dda)) {
            throw new InvalidLicenseException($this->translate('Invalid domain key.'));
        }
        if (empty($v9d6e24ec78d309695833f4c9f8310d7a)) {
            throw new InvalidLicenseException($this->translate('Do not set the default domain.'));
        }
        if ($this->getHostDomain($v10573b873d2fa5a365d558a45e328e47) !== $v9d6e24ec78d309695833f4c9f8310d7a) {
            throw new InvalidLicenseException($this->translate('Invalid domain key for domain.'));
        }
        $ve565cc8c290304c689cca058f16d317d = $this->getSiteSettings()
            ->get('licenseType');
        if (empty($ve565cc8c290304c689cca058f16d317d)) {
            throw new InvalidLicenseException($this->translate('Wrong license type.'));
        }
        if (strstr($ve565cc8c290304c689cca058f16d317d, base64_decode('dHJpYWw='))) {
            $v8f5165870c7edf106fbe43ec9ab7d354 = $this->getSiteSettings()
                ->get('deactivation');
            if (empty($v8f5165870c7edf106fbe43ec9ab7d354) || base64_decode($v8f5165870c7edf106fbe43ec9ab7d354) < time()
            ) {
                throw new InvalidLicenseException($this->translate('License has expired.'));
            }
        }
        if (!$this->checkDomainKey($v10573b873d2fa5a365d558a45e328e47)) {
            throw new InvalidLicenseException($this->translate('Invalid domain key.'));
        }
    }

    private function getSourceDomainKey(Request $v10573b873d2fa5a365d558a45e328e47, $v718779752b851ac0dc6281a8c8d77e7e)
    {
        $v836c673259e51101a01e755a34f97359 = $v10573b873d2fa5a365d558a45e328e47->server->get('SERVER_ADDR');
        $v9d6e24ec78d309695833f4c9f8310d7a = $this->getDefaultDomain();
        $v90fdeb3fda515dc805fa06fda3504d5c = strtoupper(
            substr(md5($v836c673259e51101a01e755a34f97359), 0, 11) . "-" . substr(
                md5($v9d6e24ec78d309695833f4c9f8310d7a . $v718779752b851ac0dc6281a8c8d77e7e),
                0,
                11
            )
        );

        return $v90fdeb3fda515dc805fa06fda3504d5c;
    }

    private function getDefaultDomain()
    {
        $v9d6e24ec78d309695833f4c9f8310d7a = $this->getSiteSettings()
            ->get('defaultDomain');
        if (mb_strrpos($v9d6e24ec78d309695833f4c9f8310d7a, 'www.') === 0) {
            $v9d6e24ec78d309695833f4c9f8310d7a = mb_substr($v9d6e24ec78d309695833f4c9f8310d7a, 4);
        }

        return $v9d6e24ec78d309695833f4c9f8310d7a;
    }

    private function getHostDomain(Request $v10573b873d2fa5a365d558a45e328e47)
    {
        $v67b3dba8bc6778101892eb77249db32e = $v10573b873d2fa5a365d558a45e328e47->getHost();
        if (mb_strrpos($v67b3dba8bc6778101892eb77249db32e, 'www.') === 0) {
            $v67b3dba8bc6778101892eb77249db32e = mb_substr($v67b3dba8bc6778101892eb77249db32e, 4);
        }

        return $v67b3dba8bc6778101892eb77249db32e;
    }

    private function checkDomainKey(Request $v10573b873d2fa5a365d558a45e328e47)
    {
        $v2b3291e2ad6ca2773e393d0f77003dda = $this->getSiteSettings()
            ->get('domainKey');
        $ve565cc8c290304c689cca058f16d317d = $this->getSiteSettings()
            ->get('licenseType');
        $v8fa1ae992bb04ae721b564bb29750dfe = $this->getSourceDomainKey(
            $v10573b873d2fa5a365d558a45e328e47,
            $ve565cc8c290304c689cca058f16d317d
        );

        return (substr(
                $v2b3291e2ad6ca2773e393d0f77003dda,
                12,
                strlen($v2b3291e2ad6ca2773e393d0f77003dda) - 12
            ) == $v8fa1ae992bb04ae721b564bb29750dfe);
    }
}