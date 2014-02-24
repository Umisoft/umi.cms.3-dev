<?php
namespace umicms\application\install\action;

use umi\db\cluster\IDbCluster;
use umi\db\cluster\server\IServer;
use umi\http\request\IRequest;
use umi\mvc\controller\BaseViewModelController;
use umi\orm\object\IHierarchicObject;
use umi\orm\object\IObject;
use umi\orm\toolbox\IORMPersistAware;
use umi\orm\toolbox\IORMTools;
use umi\orm\toolbox\TORMCollectionAware;
use umi\orm\toolbox\TORMPersistAware;
use umi\session\ISessionManager;
use umicms\api\users\user\AuthUser;
use umicms\api\users\UsersApi;

/**
 * Действие, вызываемое для установки
 * TODO: временный инсталлятор
 */
class InstallAction extends BaseViewModelController implements IORMPersistAware {

    /**
     * @var string $template шаблон сетки
     */
    protected $template = 'layout.phtml';
    /**
     * @var IDbCluster $dbCluster
     */
    protected $dbCluster;
    /**
     * @var IORMTools $ORMTools
     */
    protected $ORMTools;
    /**
     * @var UsersApi $usersApi
     */
    protected $usersApi;

    /**
     * Конструктор.
     * @param IDbCluster $dbCluster
     * @param \umicms\api\users\UsersApi $usersApi
     * @param \umi\session\ISessionManager $session
     */
    public function __construct(IDbCluster $dbCluster, UsersApi $usersApi, ISessionManager $session) {
        $this->dbCluster = $dbCluster;
        $this->usersApi = $usersApi;
        $session->destroy();
    }

    /**
     * Устанавливает инструментарий ORM
     * @param IORMTools $ORMTools
     * @return self
     */
    public function setORMTools(IORMTools $ORMTools) {
        $this->ORMTools = $ORMTools;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(IRequest $request) {
        $this->installDemo();

        return $this->result('action/layout', [
                'content' => 'installed',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function commit() {
        return $this->ORMTools->getObjectPersister()->commit();
    }

    /**
     * Устанавливает демо-структуру
     */
    protected function installDemo() {
        $this->prepareTables();
        $this->prepareData();
    }

    private function prepareTables() {

        $migrations = [];
        foreach ($this->ORMTools->getCollectionManager()->getList() as $collectionName) {
            $editableMetadata = $this->ORMTools->getMetadataManager()->getEditableMetadata($collectionName);
            $tableName = $editableMetadata->getCollectionDataSource()->getSourceName();

            $server = $dataSource = $editableMetadata->getCollectionDataSource()->getMasterServer();
            $dbDriver = $server->getDbDriver();

            $dbDriver->disableForeignKeysCheck();
            $dbDriver->dropTable($tableName);

            if ($editableMetadata->getCollectionDataSourceScheme()->getIsModified()) {
                $dataSource = $editableMetadata->getCollectionDataSource();
                $migrations[$collectionName] = [
                    $dataSource->getMasterServer(),
                    $editableMetadata->getCollectionDataSourceScheme()->getMigrationQueries()
                ];
            }

            $dbDriver->enableForeignKeysCheck();
        }

        foreach ($migrations as $collectionMigrations) {
            /**
             * @var IServer $server
             */
            $server = $collectionMigrations[0];
            $queries = $collectionMigrations[1];

            $server->getDbDriver()->disableForeignKeysCheck();
            foreach ($queries as $query) {
                $server->modifyInternal($query);
            }
            $server->getDbDriver()->enableForeignKeysCheck();
            $server->getDbDriver()->reset();
        }
        $this->ORMTools->getMetadataManager()->applyMetadataModifications();
    }

    private function prepareData() {

        $site = $this->addSite();
        $layout = $this->addLayout();

        $site->setValue('layout', $layout);
        $layout->setValue('site', $site);

        $this->addContentPages($site, $layout);
        $this->addNews($site, $layout);
        $this->addGratitude($site, $layout);
        $this->addBlogs($site, $layout);
        $this->addUsers();

        $this->ORMTools->getObjectPersister()->commit();
    }

    /**
     * Создает сайт
     * @return IHierarchicObject
     */
    private function addSite() {
        $collectionManager = $this->ORMTools->getCollectionManager();
        $siteCollection = $collectionManager->getCollection('system_site');

        /**
         * @var IHierarchicObject $site
         */
        $site = $siteCollection->add()
            ->setValue('displayName', 'Test site')
            ->setValue('source', 'hunters')
            ->setValue('displayName', 'Охотницы');
        $site->setSlug('hunters')
            ->setGUID('8650706f-04ca-49b6-a93d-966a42377a6f');

        return $site;
    }

    /**
     * Создает layout для сайта
     * @return IObject
     */
    private function addLayout() {
        $collectionManager = $this->ORMTools->getCollectionManager();
        $layoutCollection = $collectionManager->getCollection('system_layout');

        $layout = $layoutCollection->add()
            ->setValue('source', 'default')
            ->setValue('displayName', 'Основной умолчанию')
            ->setGUID('d6cb8b38-7e2d-4b36-8d15-9fe8947d66c7');

        return $layout;
    }

    /**
     * Создает страницы контента
     * @param IHierarchicObject $site
     * @param IObject $layout
     */
    private function addContentPages(IHierarchicObject $site, IObject $layout) {
        $collectionManager = $this->ORMTools->getCollectionManager();
        $contentCollection = $collectionManager->getCollection('content_page');

        /**
         * @var IHierarchicObject $about
         */
        $about = $contentCollection->add()
            ->setValue('displayName', 'Об отряде')
            ->setValue('metaTitle', 'Об отряде')
            ->setValue('h1', 'Об отряде')
            ->setValue('displayName', 'Об отряде')
            ->setValue('content', '<p>Мы &mdash; отряд Охотниц за привидениями. Цвет волос, уровень IQ, размер груди, длина ног и количество высших образований не оказывают существенного влияния при отборе кадров в наши подразделения.</p><p>Единственно значимым критерием является наличие у Охотницы следующих навыков:</p><blockquote>метод десятипальцевой печати;<br /> тайский массаж;<br /> метод левой руки;<br /> техника скорочтения;</blockquote><p>Миссия нашей компании: Спасение людей от привидений во имя спокойствия самих привидений.<br /><br /> 12 лет нашей работы доказали, что предлагаемые нами услуги востребованы человечеством. За это время мы получили:</p><blockquote>1588 искренних благодарностей от клиентов; <br /> 260080 комплиментов; <br /> 5 интересных предложений руки и сердца.</blockquote><p>Нам не только удалось пережить кризис августа 1998 года, но и выйти на новый, рекордный уровень рентабельности.<br /> В своей работе мы используем             <strong>сверхсекретные</strong> супер-пупер-технологии.</p>')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $about
            ->setParent($site)
            ->setSlug('ob_otryade')
            ->setGUID('d534fd83-0f12-4a0d-9853-583b9181a948');

        $site->setValue('defaultPage', $about);

        /**
         * @var IHierarchicObject $ourWork
         */
        $ourWork = $contentCollection->add()
            ->setValue('displayName', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('metaTitle', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('h1', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('displayName', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('layout', $layout)
            ->setValue('content', '<ul><li>Безосновательный вызов призраков на дом</li><li>Гадания на картах, кофейной гуще, блюдечке</li><li>Толкование снов</li><li>Интим-услуги. Мы не такие!</li></ul>');
        $ourWork
            ->setParent($about)
            ->setSlug('no')
            ->setGUID('3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4');

        /**
         * @var IHierarchicObject $services
         */
        $services = $contentCollection->add()
            ->setValue('displayName', 'Услуги')
            ->setValue('metaTitle', 'Услуги')
            ->setValue('h1', 'Услуги')
            ->setValue('displayName', 'Услуги')
            ->setValue('content', '<p><strong>Дипломатические переговоры с домовыми</strong></p><p>Домовые требуют особого подхода. Выгонять домового из дома категорически запрещено, т.к. его призвание &mdash; охранять дом. Однако, некоторые домовые приносят своим хозяевам немало хлопот из-за своенравного характера. <br /><br />Хорошие отношения с домовым &mdash; наша работы. Правильно провести дипломатические переговоры с домовым, с учетом его знака зодиака, типа температмента и других психографических характеристик, настроить его на позитивный лад, избавить от личных переживаний, разобраться в ваших разногласиях и провести результативные переговоры может грамотный специалист с широким набором характеристик и знаний.<br /><br /><em>Работает Охотница Ольга Карпова <br />Спецнавыки: паранормальная дипломатия, психология поведения духов и разрешение конфликтов</em></p><p><br /><br /><strong>Изгнание призраков царских кровей и других элитных духов<br /></strong><br />Вы купили замок? Хотите провести профилактические работы? Или уже столкнулись с присутствием призраков один на один?<br /><br />Вам &mdash; в наше элитное подразделение. Духи царских кровей отличаются кичливым поведением и высокомерием, однако до сих пор подразделение Охотниц в бикини всегда справлялось с поставленными задачами.<br /><br />Среди наших побед:</p><p>- тень отца Гамлета, вызвавшая переполох в женской раздевалке фитнес-клуба; <br />- призрак Ленина, пытающийся заказать роллы Калифорния на вынос; <br />- призрак Цезаря на неделе миланской моды в Москве.&nbsp; <br /><br /><em>Работает Охотница Елена&nbsp; Жарова <br />Спецнавыки: искусство душевного разговора</em></p>')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $services
            ->setParent($site)
            ->setSlug('services')
            ->setGUID('98751ebf-7f76-4edb-8210-c2c3305bd8a0');

        /**
         * @var IHierarchicObject $prices
         */
        $prices = $contentCollection->add()
            ->setValue('displayName', 'Тарифы и цены')
            ->setValue('metaTitle', 'Тарифы и цены')
            ->setValue('h1', 'Тарифы и цены')
            ->setValue('displayName', 'Тарифы и цены')
            ->setValue('content', '<p><strong>Если вас регулярно посещают привидения, призраки, НЛО, &laquo;Летучий голландец&raquo;, феномен черных рук, демоны, фантомы, вампиры и чупакабры...</strong></p><p>Мы предлагаем вам воспользоваться нашим <strong>тарифом абонентской платы</strong>, который составляет <span style="color: #ff6600;"><strong>1 995</strong></span> у.е. в год. Счастливый год без привидений!</p><p><strong>Если паранормальное явление появился в вашей жизни неожиданно, знакомьтесь с прайсом*:<br /></strong></p><blockquote>Дипломатические переговоры с домовым &ndash; <span style="color: #ff6600;"><strong>120</strong></span> у.е.<br />Нейтрализация вампира &ndash; <span style="color: #ff6600;"><strong>300</strong></span> у.е.<br />Изгнание привидения стандартного &ndash; <span style="color: #ff6600;"><strong>200</strong></span> у.е.<br />Изгнание привидений царей, принцев и принцесс, вождей революций и другой элиты &ndash; <span style="color: #ff6600;"><strong>1250</strong></span> у.е.<br />Борьба с НЛО &ndash; рассчитывается <span style="text-decoration: underline;">индивидуально</span>.</blockquote><p><strong>Специальная услуга: </strong>ВЫЗОВ ОТРЯДА В БИКИНИ</p><p><span style="font-size: x-small;"><em>Стандартные услуги в сочетании с эстетическим удовольствием!</em></span></p><p><strong>Скидки оптовым и постоянным клиентам:</strong><br />При заказе устранения от 5 духов (любого происхождения, включая элиту) предоставляется скидка 12% от общей цены. Скидки по акциям не суммируются.</p><p><span>*Цена за одну особь!</span></p>')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $prices
            ->setParent($site)
            ->setSlug('price')
            ->setGUID('c81d6d87-25c6-4ab8-b213-ef3a0f044ce6');

        /*$firstPage = $contentCollection->add()
            ->setValue('displayName', 'Первая страница')
            ->setValue('inMenu', true)
            ->setValue('submenuState', CMSPage::SUBMENU_ALWAYS_SHOWN)
            ->setValue('layout', $layout)
            ->setParent($site)
            ->setSlug('first_page');

        $secondPage = $contentCollection->add()
            ->setValue('displayName', 'Вторая страница')
            ->setValue('inMenu', true)
            ->setValue('submenuState', CMSPage::SUBMENU_CURRENT_SHOWN)
            ->setValue('layout', $layout)
            ->setParent($firstPage)
            ->setSlug('second_page');

        $thirdPage = $contentCollection->add()
            ->setValue('displayName', 'Третья страница')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout)
            ->setParent($secondPage)
            ->setSlug('third_page');

        $forthPage = $contentCollection->add()
            ->setValue('displayName', 'Четвертая страница')
            ->setValue('layout', $layout)
            ->setParent($thirdPage)
            ->setSlug('forth_page');*/
    }

    /**
     * Создает новости
     * @param IHierarchicObject $site
     * @param IObject $layout
     */
    private function addNews(IHierarchicObject $site, IObject $layout) {

        $collectionManager = $this->ORMTools->getCollectionManager();
        $newsCategoryCollection = $collectionManager->getCollection('news_category');
        $newsCollection = $collectionManager->getCollection('news_news_item');

        /**
         * @var IHierarchicObject $news
         */
        $news = $newsCategoryCollection->add()
            ->setValue('displayName', 'Новости')
            ->setValue('metaTitle', 'Новости')
            ->setValue('h1', 'Новости')
            ->setValue('displayName', 'Новости')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $news->setParent($site)
            ->setSlug('news')
            ->setGUID('9ee6745f-f40d-46d8-8043-d959594628ce');

        $newsCollection->add()
            ->setValue('displayName', 'Названа причина социопатии современных зомби')
            ->setValue('metaTitle', 'Названа причина социопатии современных зомби')
            ->setValue('h1', 'Названа причина социопатии современных зомби')
            ->setValue('displayName', 'Названа причина социопатии современных зомби')
            ->setValue('announcement', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.</p>')
            ->setValue('content', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.  Ученые давно бьют тревогу по поводу образа жизни молодых зомби и сейчас активно занялись пропагандой спорта, фитнес-клубов, активных игр на воздухе и популяризацией вегетарианской пищи среди представителей этого вида.  Пока ученые занимаются всеми этими вещами, молодые зомби курят по подъездам, впадают в депрессивные состоянии, примыкают к эмо-группировкам и совершенно не хотят работать.  &laquo;А между тем, этих ребят еще можно спасти, &mdash; комментирует Виктория Евдокимова, Охотница за привидениями со стажем, &mdash; и это в силах каждого из нас. Если увидите на улице одинокого зомби, подойдите и поинтересуйтесь, как обстоят дела с его девчонкой, какие у него планы на выходные, и что он делал прошлым летом&raquo;.</p>')
            ->setValue('date', '2010-08-01 17:34:00')
            ->setValue('parent', $news)
            ->setGUID('d6eb9ad1-667e-429d-a476-fa64c5eec115')
            ->setValue('slug', 'zombi');

        $newsCollection->add()
            ->setValue('displayName', 'Смена состава в Отряде в бикини')
            ->setValue('metaTitle', 'Смена состава в Отряде в бикини')
            ->setValue('h1', 'Смена состава в Отряде в бикини')
            ->setValue('displayName', 'Смена состава в Отряде в бикини')
            ->setValue('announcement', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.</p>')
            ->setValue('content', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.  Маша Шикова имеет большой опыт в борьбе с домашними призраками и два столкновения с вампирами. Новая Охотница прекрасно вписалась в наш дружный женский коллектив и в ожидании интересных заданий уже пополнила свой гардероб пятью новыми комплектами бикини.   Лолита Андреева на редкость вяло комментирует свой выход из отряда. По нашим данным, это связано с тем, что маникюрный мастер девушки, с которым у нее был длительный роман, без предупреждения уехал в отпуск на Бали и оставил ее "подыхать в одиночестве".</p>')
            ->setValue('date', '2010-08-03 17:36:00')
            ->setValue('parent', $news)
            ->setGUID('35806ed8-1306-41b5-bbf9-fe2faedfc835')
            ->setValue('slug', 'bikini');

        $newsCollection->add()
            ->setValue('displayName', 'Открыт метод устранения неврозов у привидений')
            ->setValue('metaTitle', 'Открыт метод устранения неврозов у привидений')
            ->setValue('h1', 'Открыт метод устранения неврозов у привидений')
            ->setValue('displayName', 'Открыт метод устранения неврозов у привидений')
            ->setValue('announcement', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина<br />Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим<br />средством воздействия на привидения были, есть и будут красивые женские<br />ноги.</p>')
            ->setValue('content', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим средством воздействия на привидения были, есть и будут красивые женские ноги.  &laquo;Я долго шла к этому открытию, и на пути к нему совершила много других маленьких открытий, однако лучшее практическое применение получили именно мои ноги&raquo;, &mdash; рассказывает первооткрывательница.  В своем масштабном научном труде она дает рекомендации по правильному применению метода среди призраков и людей, а также эффективной длине юбке и оптимальной высоте каблука.</p>')
            ->setValue('date', '2010-08-02 17:35:00')
            ->setValue('parent', $news)
            ->setGUID('96a6bea4-3c77-4ea1-9eb3-c4b1082253db')
            ->setValue('slug', 'privideniya');

    }

    /**
     * Создает благодарности
     * @param IHierarchicObject $site
     * @param IObject $layout
     */
    private function addGratitude(IHierarchicObject $site, IObject $layout) {

        $collectionManager = $this->ORMTools->getCollectionManager();
        $newsCategoryCollection = $collectionManager->getCollection('news_category');
        $newsCollection = $collectionManager->getCollection('news_news_item');

        /**
         * @var IHierarchicObject $gratitude
         */
        $gratitude = $newsCategoryCollection->add()
            ->setValue('displayName', 'Благодарности')
            ->setValue('metaTitle', 'Благодарности')
            ->setValue('h1', 'Благодарности')
            ->setValue('displayName', 'Благодарности')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $gratitude
            ->setParent($site)
            ->setSlug('gratitude')
            ->setGUID('4430239f-77f4-464d-b9eb-46f4c93eee8c');


        $newsCollection->add()
            ->setValue('displayName', 'Наташа')
            ->setValue('metaTitle', 'Наташа Рублева, домохозяйка')
            ->setValue('h1', 'Наташа Рублева, домохозяйка')
            ->setValue('displayName', 'Наташа Рублева, домохозяйка')
            ->setValue('announcement', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло</p>')
            ->setValue('content', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло. Я вызвала наряд охотниц за привидениями, и теперь мы избавлены от этих проблем. Сотрудница организации рекомендовала мне воспользоваться услугами спецподразделения &laquo;Отряд в бикини&raquo;. Я не пожалела, и, кажется, муж остался доволен.</p>')
            ->setValue('date', '2013-06-24 19:11')
            ->setValue('parent', $gratitude)
            ->setValue('slug', 'natasha')
            ->setGUID('da5ec9a8-229c-4120-949c-2bb9eb641f24');

        $newsCollection->add()
            ->setValue('displayName', 'Александр')
            ->setValue('metaTitle', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('h1', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('displayName', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('announcement', '<p>С 18 лет меня довольно регулярно похищали инопланетяне.&nbsp;Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде</p>')
            ->setValue('content', '<p>С 18 лет меня довольно регулярно похищали инопланетяне. Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде &ndash; я перестал смыслить свою жизнь без пива и чипсов. Я был вынужден обратиться к профессионалам. Как мне помогли Охотницы? Инициировав повторный сеанс связи, они совершили настоящий переворот. Теперь я замечательно обхожусь пряниками и шоколадом. Особую благодарность хочу выразить Охотнице Елене Жаровой за красивые глаза.</p>')
            ->setValue('date', '2013-06-24 19:14')
            ->setValue('parent', $gratitude)
            ->setGUID('60744128-996a-4cea-a937-c20ebc5c8c77')
            ->setValue('slug', 'aleksandr');

    }

    private function addBlogs(IHierarchicObject $site, IObject $layout) {

        $collectionManager = $this->ORMTools->getCollectionManager();

        $blogCollection = $collectionManager->getCollection('blogs_blog');
        $postCollection = $collectionManager->getCollection('blogs_post');
        $commentCollection = $collectionManager->getCollection('blogs_comment');

        /**
         * @var IHierarchicObject $blog
         */
        $blog = $blogCollection->add()
            ->setValue('displayName', 'Блог')
            ->setValue('metaTitle', 'Блог Охотниц за приведениями')
            ->setValue('h1', 'Блог Охотниц за приведениями')
            ->setValue('displayName', 'Блог Охотниц за приведениями')
            ->setValue('content', '<p>Это блого обо всем на свете...</p>')
            ->setValue('inMenu', true)
            ->setValue('layout', $layout);
        $blog
            ->setParent($site)
            ->setSlug('blog')
            ->setGUID('7865b444-6799-45d6-9145-93a614bdfab7');

        /**
         * @var IHierarchicObject $post1
         */
        $post1 = $postCollection->add()
            ->setValue('displayName', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('metaTitle', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('h1', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>Причины девиантного поведения домашних призраков кроются безусловно во влиянии MTV и пропаганде агрессивной альтернативной музыки.<br /><br />Также наблюдается рост домовых, практикующих экстремальное катание на роликовых коньках, скейт-бордах, BMX, что повышает общий уровень черепно-мозговых травм среди паранормальных существ. <br /><br />Не может не оказывать влияния проникновение культуры эмо в быт и уклад домашних призраков, что ведет к росту самоубийств и депрессивных состояний среди этих в общем-то жизнерадостных<br /> созданий.<br /><br />В качестве метода влияния на отклонения у домашний призраков я вижу их обращение в более позитивные и миролюбивые культуры, их пропаганда и популяризация в среде домашних призраков.<br /><br /><strong>Екатерина Джа-Дуплинская</strong></p>')
            ->setValue('date', '2010-08-11 17:35:00')
            ->setValue('layout', $layout);
        $post1
            ->setParent($blog)
            ->setSlug('deviant')
            ->setGUID('8e675484-bea4-4fb5-9802-4750cc21e509');

        $comment1 = $commentCollection->add()
            ->setValue('displayName', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('metaTitle', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>О да. Недавно в нашем замке один милый маленький призрак покончил с собой. Мы были уверены, что это невозможно, но каким-то образом ему удалось раствориться в воде, наполняющей наш древний колодец.</p>')
            ->setValue('date', '2012-11-15 15:07:31');

        $comment2 = $commentCollection->add()
            ->setValue('displayName', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('metaTitle', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>Возможно, вашего призрака еще удастся спасти. Попробуйте насыпать в колодец пару столовых ложек молотых семян бессмертника. Это должно помочь призраку снова сконденсировать свое нематериальное тело. И да, важно, чтобы семена были собраны в новолуние.</p>')
            ->setValue('date', '2012-11-15 15:11:21')
            ->setValue('parent', $comment1);

        $post1->setValue('firstComment', $comment1);

        /**
         * @var IHierarchicObject $post2
         */
        $post2 = $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('content', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('date', '2010-08-14 17:35:00')
            ->setValue('layout', $layout);
        $post2
            ->setParent($blog)
            ->setSlug('razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty_litvinovoj')
            ->setGUID('2ff677ee-765c-42ee-bb97-778f03f00c50');

        $comment3 = $commentCollection->add()
            ->setValue('displayName', 'важный вопрос')
            ->setValue('metaTitle', 'важный вопрос')
            ->setValue('displayName', 'важный вопрос')
            ->setValue('content', '<p>Существует ли разговорник для общения с НЛО? Основы этикета?</p>')
            ->setValue('date', '2012-11-15 15:05:34');

        $post2->setValue('firstComment', $comment3);
    }

    private function addUsers() {

        $collectionManager = $this->ORMTools->getCollectionManager();
        $userCollection = $collectionManager->getCollection('users_user');

        $userCollection->add('guest')
            ->setValue('displayName', 'Гость')
            ->setValue('login', 'guest')
            ->setGUID('9f94724d-a0e3-4e7a-9179-d3ddaa7d3941');

        /**
         * @var AuthUser $sv
         */
        $sv = $userCollection->add('user')
            ->setValue(AuthUser::FIELD_DISPLAY_NAME, 'Супервайзер')
            ->setValue(AuthUser::FIELD_LOGIN, 'sv')
            ->setGUID('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');
        $this->usersApi->setUserPassword($sv, '1');
    }

}