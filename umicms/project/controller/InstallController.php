<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\controller;

use umi\dbal\cluster\IDbCluster;
use umi\dbal\driver\IDialect;
use umi\http\Response;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\SimpleCollection;
use umi\orm\collection\SimpleHierarchicCollection;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\library\controller\BaseController;
use umicms\project\module\structure\model\StaticPage;

/**
 * Class InstallController
 */
class InstallController extends BaseController implements ICollectionManagerAware, IObjectPersisterAware
{

    use TCollectionManagerAware;
    use TObjectPersisterAware;

    /**
     * @var IDbCluster $dbCluster
     */
    protected $dbCluster;

    public function __construct(IDbCluster $dbCluster)
    {
        $this->dbCluster = $dbCluster;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $this->installDbStructure();

        $this->installStaticPages();
        $this->installNews();
        $this->installGratitude();
        $this->installBlog();

        $this->getObjectPersister()->commit();

        return $this->createResponse('Installed');
    }

    protected function installBlog()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var SimpleHierarchicCollection $categoriesCollection
         */
        $categoriesCollection = $this->getCollectionManager()->getCollection('blog_category');
        /**
         * @var SimpleCollection $postCollection
         */
        $postCollection = $this->getCollectionManager()->getCollection('blog_post');
        /**
         * @var SimpleHierarchicCollection $commentCollection
         */
        $commentCollection = $this->getCollectionManager()->getCollection('blog_comment');


        $structureCollection->add('blog', 'system')
            ->setValue('displayName', 'Блог')
            ->setGUID('9aa6745f-f40d-5489-8043-d959594123ce')
            ->getProperty('module')->setValue('blog');

        $blog = $categoriesCollection->add('company')
            ->setValue('displayName', 'Блог')
            ->setValue('metaTitle', 'Блог Охотниц за приведениями')
            ->setValue('h1', 'Блог Охотниц за приведениями')
            ->setValue('content', '<p>Это блого обо всем на свете...</p>')
            ->setGUID('7865b444-6799-45d6-9145-93a614bdfab7');

        $post1 = $postCollection->add()
            ->setValue('displayName', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('metaTitle', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('h1', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>Причины девиантного поведения домашних призраков кроются безусловно во влиянии MTV и пропаганде агрессивной альтернативной музыки.<br /><br />Также наблюдается рост домовых, практикующих экстремальное катание на роликовых коньках, скейт-бордах, BMX, что повышает общий уровень черепно-мозговых травм среди паранормальных существ. <br /><br />Не может не оказывать влияния проникновение культуры эмо в быт и уклад домашних призраков, что ведет к росту самоубийств и депрессивных состояний среди этих в общем-то жизнерадостных<br /> созданий.<br /><br />В качестве метода влияния на отклонения у домашний призраков я вижу их обращение в более позитивные и миролюбивые культуры, их пропаганда и популяризация в среде домашних призраков.<br /><br /><strong>Екатерина Джа-Дуплинская</strong></p>')
            ->setValue('date', '2010-08-11 17:35:00')
            ->setValue('slug', 'deviant')
            ->setGUID('8e675484-bea4-4fb5-9802-4750cc21e509');

        /**
         * @var IHierarchicObject $comment1
         */
        $comment1 = $commentCollection->add('comment1')
            ->setValue('displayName', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>О да. Недавно в нашем замке один милый маленький призрак покончил с собой. Мы были уверены, что это невозможно, но каким-то образом ему удалось раствориться в воде, наполняющей наш древний колодец.</p>')
            ->setValue('date', '2012-11-15 15:07:31')
            ->setValue('post', $post1);

        $commentCollection->add('comment2', IObjectType::BASE, $comment1)
            ->setValue('displayName', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('content', '<p>Возможно, вашего призрака еще удастся спасти. Попробуйте насыпать в колодец пару столовых ложек молотых семян бессмертника. Это должно помочь призраку снова сконденсировать свое нематериальное тело. И да, важно, чтобы семена были собраны в новолуние.</p>')
            ->setValue('date', '2012-11-15 15:11:21')
            ->setValue('post', $post1);

        $post2 = $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('content', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('date', '2010-08-14 17:35:00')
            ->setValue('category', $blog)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty_litvinovoj')
            ->setGUID('2ff677ee-765c-42ee-bb97-778f03f00c50');

        $commentCollection->add('comment3')
            ->setValue('displayName', 'важный вопрос')
            ->setValue('content', '<p>Существует ли разговорник для общения с НЛО? Основы этикета?</p>')
            ->setValue('date', '2012-11-15 15:05:34')
            ->setValue('post', $post2);

    }

    protected function installNews()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var SimpleCollection $newsCollection
         */
        $newsCollection = $this->getCollectionManager()->getCollection('news_news_item');
        /**
         * @var SimpleHierarchicCollection $categoriesCollection
         */
        $categoriesCollection = $this->getCollectionManager()->getCollection('news_category');

        $structureCollection->add('news', 'system')
            ->setValue('displayName', 'Новости')
            ->setGUID('9ee6745f-f40d-46d8-8043-d959594628ce')
            ->getProperty('module')->setValue('news');

        $newsCategory = $categoriesCollection->add('company')
            ->setValue('displayName', 'Новости сайта')
            ->setValue('metaTitle', 'Новости сайта')
            ->setValue('h1', 'Новости сайта')
            ->setGUID('8650706f-04ca-49b6-a93d-966a42377a61');

        $newsCollection->add()
            ->setValue('displayName', 'Названа причина социопатии современных зомби')
            ->setValue('metaTitle', 'Названа причина социопатии современных зомби')
            ->setValue('h1', 'Названа причина социопатии современных зомби')
            ->setValue('announcement', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.</p>')
            ->setValue('content', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.  Ученые давно бьют тревогу по поводу образа жизни молодых зомби и сейчас активно занялись пропагандой спорта, фитнес-клубов, активных игр на воздухе и популяризацией вегетарианской пищи среди представителей этого вида.  Пока ученые занимаются всеми этими вещами, молодые зомби курят по подъездам, впадают в депрессивные состоянии, примыкают к эмо-группировкам и совершенно не хотят работать.  &laquo;А между тем, этих ребят еще можно спасти, &mdash; комментирует Виктория Евдокимова, Охотница за привидениями со стажем, &mdash; и это в силах каждого из нас. Если увидите на улице одинокого зомби, подойдите и поинтересуйтесь, как обстоят дела с его девчонкой, какие у него планы на выходные, и что он делал прошлым летом&raquo;.</p>')
            ->setValue('date', '2010-08-01 17:34:00')
            ->setValue('category', $newsCategory)
            ->setGUID('d6eb9ad1-667e-429d-a476-fa64c5eec115')
            ->setValue('slug', 'zombi');

        $newsCollection->add()
            ->setValue('displayName', 'Смена состава в Отряде в бикини')
            ->setValue('metaTitle', 'Смена состава в Отряде в бикини')
            ->setValue('h1', 'Смена состава в Отряде в бикини')
            ->setValue('announcement', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.</p>')
            ->setValue('content', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.  Маша Шикова имеет большой опыт в борьбе с домашними призраками и два столкновения с вампирами. Новая Охотница прекрасно вписалась в наш дружный женский коллектив и в ожидании интересных заданий уже пополнила свой гардероб пятью новыми комплектами бикини.   Лолита Андреева на редкость вяло комментирует свой выход из отряда. По нашим данным, это связано с тем, что маникюрный мастер девушки, с которым у нее был длительный роман, без предупреждения уехал в отпуск на Бали и оставил ее "подыхать в одиночестве".</p>')
            ->setValue('date', '2010-08-03 17:36:00')
            ->setValue('category', $newsCategory)
            ->setGUID('35806ed8-1306-41b5-bbf9-fe2faedfc835')
            ->setValue('slug', 'bikini');

        $newsCollection->add()
            ->setValue('displayName', 'Открыт метод устранения неврозов у привидений')
            ->setValue('metaTitle', 'Открыт метод устранения неврозов у привидений')
            ->setValue('h1', 'Открыт метод устранения неврозов у привидений')
            ->setValue('announcement', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина<br />Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим<br />средством воздействия на привидения были, есть и будут красивые женские<br />ноги.</p>')
            ->setValue('content', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим средством воздействия на привидения были, есть и будут красивые женские ноги.  &laquo;Я долго шла к этому открытию, и на пути к нему совершила много других маленьких открытий, однако лучшее практическое применение получили именно мои ноги&raquo;, &mdash; рассказывает первооткрывательница.  В своем масштабном научном труде она дает рекомендации по правильному применению метода среди призраков и людей, а также эффективной длине юбке и оптимальной высоте каблука.</p>')
            ->setValue('date', '2010-08-02 17:35:00')
            ->setValue('category', $newsCategory)
            ->setGUID('96a6bea4-3c77-4ea1-9eb3-c4b1082253db')
            ->setValue('slug', 'privideniya');

    }

    protected function installGratitude() {

        /**
         * @var SimpleCollection $newsCollection
         */
        $newsCollection = $this->getCollectionManager()->getCollection('news_news_item');
        /**
         * @var SimpleHierarchicCollection $categoriesCollection
         */
        $categoriesCollection = $this->getCollectionManager()->getCollection('news_category');

        $gratitude = $categoriesCollection->add('gratitude')
            ->setValue('displayName', 'Благодарности')
            ->setValue('metaTitle', 'Благодарности')
            ->setValue('h1', 'Благодарности')
            ->setValue('displayName', 'Благодарности')
            ->setGUID('4430239f-77f4-464d-b9eb-46f4c93eee8c');


        $newsCollection->add()
            ->setValue('displayName', 'Наташа')
            ->setValue('metaTitle', 'Наташа Рублева, домохозяйка')
            ->setValue('h1', 'Наташа Рублева, домохозяйка')
            ->setValue('announcement', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло</p>')
            ->setValue('content', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло. Я вызвала наряд охотниц за привидениями, и теперь мы избавлены от этих проблем. Сотрудница организации рекомендовала мне воспользоваться услугами спецподразделения &laquo;Отряд в бикини&raquo;. Я не пожалела, и, кажется, муж остался доволен.</p>')
            ->setValue('date', '2013-06-24 19:11')
            ->setValue('category', $gratitude)
            ->setValue('slug', 'natasha')
            ->setGUID('da5ec9a8-229c-4120-949c-2bb9eb641f24');

        $newsCollection->add()
            ->setValue('displayName', 'Александр')
            ->setValue('metaTitle', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('h1', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('announcement', '<p>С 18 лет меня довольно регулярно похищали инопланетяне.&nbsp;Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде</p>')
            ->setValue('content', '<p>С 18 лет меня довольно регулярно похищали инопланетяне. Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде &ndash; я перестал смыслить свою жизнь без пива и чипсов. Я был вынужден обратиться к профессионалам. Как мне помогли Охотницы? Инициировав повторный сеанс связи, они совершили настоящий переворот. Теперь я замечательно обхожусь пряниками и шоколадом. Особую благодарность хочу выразить Охотнице Елене Жаровой за красивые глаза.</p>')
            ->setValue('date', '2013-06-24 19:14')
            ->setValue('category', $gratitude)
            ->setGUID('60744128-996a-4cea-a937-c20ebc5c8c77')
            ->setValue('slug', 'aleksandr');

    }

    protected function installStaticPages()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');

        /**
         * @var StaticPage $about
         */
        $about = $structureCollection->add('ob_otryade', 'static')
            ->setValue('displayName', 'Об отряде')
            ->setValue('metaTitle', 'Об отряде')
            ->setValue('h1', 'Об отряде')
            ->setValue('content', '<p>Мы &mdash; отряд Охотниц за привидениями. Цвет волос, уровень IQ, размер груди, длина ног и количество высших образований не оказывают существенного влияния при отборе кадров в наши подразделения.</p><p>Единственно значимым критерием является наличие у Охотницы следующих навыков:</p><blockquote>метод десятипальцевой печати;<br /> тайский массаж;<br /> метод левой руки;<br /> техника скорочтения;</blockquote><p>Миссия нашей компании: Спасение людей от привидений во имя спокойствия самих привидений.<br /><br /> 12 лет нашей работы доказали, что предлагаемые нами услуги востребованы человечеством. За это время мы получили:</p><blockquote>1588 искренних благодарностей от клиентов; <br /> 260080 комплиментов; <br /> 5 интересных предложений руки и сердца.</blockquote><p>Нам не только удалось пережить кризис августа 1998 года, но и выйти на новый, рекордный уровень рентабельности.<br /> В своей работе мы используем             <strong>сверхсекретные</strong> супер-пупер-технологии.</p>')
            ->setGUID('d534fd83-0f12-4a0d-9853-583b9181a948');

        $about->getProperty('module')->setValue('structure');

        $structureCollection->add('no', 'static', $about)
            ->setValue('displayName', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('metaTitle', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('h1', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('content', '<ul><li>Безосновательный вызов призраков на дом</li><li>Гадания на картах, кофейной гуще, блюдечке</li><li>Толкование снов</li><li>Интим-услуги. Мы не такие!</li></ul>')
            ->setGUID('3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4')
            ->getProperty('module')->setValue('structure');


        $structureCollection->add('services', 'static')
            ->setValue('displayName', 'Услуги')
            ->setValue('metaTitle', 'Услуги')
            ->setValue('h1', 'Услуги')
            ->setValue('content', '<p><strong>Дипломатические переговоры с домовыми</strong></p><p>Домовые требуют особого подхода. Выгонять домового из дома категорически запрещено, т.к. его призвание &mdash; охранять дом. Однако, некоторые домовые приносят своим хозяевам немало хлопот из-за своенравного характера. <br /><br />Хорошие отношения с домовым &mdash; наша работы. Правильно провести дипломатические переговоры с домовым, с учетом его знака зодиака, типа температмента и других психографических характеристик, настроить его на позитивный лад, избавить от личных переживаний, разобраться в ваших разногласиях и провести результативные переговоры может грамотный специалист с широким набором характеристик и знаний.<br /><br /><em>Работает Охотница Ольга Карпова <br />Спецнавыки: паранормальная дипломатия, психология поведения духов и разрешение конфликтов</em></p><p><br /><br /><strong>Изгнание призраков царских кровей и других элитных духов<br /></strong><br />Вы купили замок? Хотите провести профилактические работы? Или уже столкнулись с присутствием призраков один на один?<br /><br />Вам &mdash; в наше элитное подразделение. Духи царских кровей отличаются кичливым поведением и высокомерием, однако до сих пор подразделение Охотниц в бикини всегда справлялось с поставленными задачами.<br /><br />Среди наших побед:</p><p>- тень отца Гамлета, вызвавшая переполох в женской раздевалке фитнес-клуба; <br />- призрак Ленина, пытающийся заказать роллы Калифорния на вынос; <br />- призрак Цезаря на неделе миланской моды в Москве.&nbsp; <br /><br /><em>Работает Охотница Елена&nbsp; Жарова <br />Спецнавыки: искусство душевного разговора</em></p>')
            ->setGUID('98751ebf-7f76-4edb-8210-c2c3305bd8a0')
            ->getProperty('module')->setValue('structure');

        $structureCollection->add('price', 'static')
            ->setValue('displayName', 'Тарифы и цены')
            ->setValue('metaTitle', 'Тарифы и цены')
            ->setValue('h1', 'Тарифы и цены')
            ->setValue('content', '<p><strong>Если вас регулярно посещают привидения, призраки, НЛО, &laquo;Летучий голландец&raquo;, феномен черных рук, демоны, фантомы, вампиры и чупакабры...</strong></p><p>Мы предлагаем вам воспользоваться нашим <strong>тарифом абонентской платы</strong>, который составляет <span style="color: #ff6600;"><strong>1 995</strong></span> у.е. в год. Счастливый год без привидений!</p><p><strong>Если паранормальное явление появился в вашей жизни неожиданно, знакомьтесь с прайсом*:<br /></strong></p><blockquote>Дипломатические переговоры с домовым &ndash; <span style="color: #ff6600;"><strong>120</strong></span> у.е.<br />Нейтрализация вампира &ndash; <span style="color: #ff6600;"><strong>300</strong></span> у.е.<br />Изгнание привидения стандартного &ndash; <span style="color: #ff6600;"><strong>200</strong></span> у.е.<br />Изгнание привидений царей, принцев и принцесс, вождей революций и другой элиты &ndash; <span style="color: #ff6600;"><strong>1250</strong></span> у.е.<br />Борьба с НЛО &ndash; рассчитывается <span style="text-decoration: underline;">индивидуально</span>.</blockquote><p><strong>Специальная услуга: </strong>ВЫЗОВ ОТРЯДА В БИКИНИ</p><p><span style="font-size: x-small;"><em>Стандартные услуги в сочетании с эстетическим удовольствием!</em></span></p><p><strong>Скидки оптовым и постоянным клиентам:</strong><br />При заказе устранения от 5 духов (любого происхождения, включая элиту) предоставляется скидка 12% от общей цены. Скидки по акциям не суммируются.</p><p><span>*Цена за одну особь!</span></p>')
            ->setGUID('c81d6d87-25c6-4ab8-b213-ef3a0f044ce6')
            ->getProperty('module')->setValue('structure');

    }

    protected function installDbStructure()
    {
        $connection = $this->dbCluster->getConnection();
        /**
         * @var IDialect $dialect
         */
        $dialect = $connection->getDatabasePlatform();
        $connection->exec($dialect->getDisableForeignKeysSQL());

        $this->installStructureTable();
        $this->installNewsTables();
        $this->installBlogTables();

        $connection->exec($dialect->getEnableForeignKeysSQL());
    }

    protected function installBlogTables()
    {
        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_blog_category`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_blog_post`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_blog_comment`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_blog_tag`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_blog_post_tag`");

        $connection->exec(
            "
                CREATE TABLE `demohunt_blog_category` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',

                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255) DEFAULT NULL,
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',

                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_category_guid` (`guid`),
                    UNIQUE KEY `blog_category_pid_slug` (`pid`, `slug`),
                    KEY `blog_category_type` (`type`),
                    KEY `blog_category_pid` (`pid`),
                    CONSTRAINT `FK_blog_category_pid` FOREIGN KEY (`pid`) REFERENCES `demohunt_blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_blog_post` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255) DEFAULT NULL,
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `date` datetime DEFAULT NULL,
                    `content` text,
                    `announcement` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `category_id` bigint(20) unsigned DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_post_guid` (`guid`),
                    UNIQUE KEY `blog_post_slug` (`slug`),
                    KEY `blog_post_type` (`type`),
                    KEY `blog_post_category` (`category_id`),
                    CONSTRAINT `FK_blog_post_category` FOREIGN KEY (`category_id`) REFERENCES `demohunt_blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_blog_tag` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255) DEFAULT NULL,
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_tag_guid` (`guid`),
                    UNIQUE KEY `blog_tag_slug` (`slug`),
                    KEY `blog_tag_type` (`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_blog_post_tag` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `post_id` bigint(20) unsigned,
                    `tag_id` bigint(20) unsigned,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `post_tag_guid` (`guid`),
                    KEY `post_tag_type` (`type`),
                    KEY `post_tag_tag` (`tag_id`),
                    KEY `post_tag_post` (`post_id`),
                    CONSTRAINT `FK_post_tag_tag` FOREIGN KEY (`tag_id`) REFERENCES `demohunt_blog_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_post_tag_post` FOREIGN KEY (`post_id`) REFERENCES `demohunt_blog_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_blog_comment` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255) DEFAULT NULL,
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `post_id` bigint(20) unsigned,
                    `date` datetime DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `blog_comment_guid` (`guid`),
                    UNIQUE KEY `blog_comment_pid_slug` (`pid`, `slug`),
                    KEY `blog_comment_type` (`type`),
                    KEY `blog_comment_pid` (`pid`),
                    KEY `blog_comment_post` (`post_id`),
                    CONSTRAINT `FK_blog_comment_pid` FOREIGN KEY (`pid`) REFERENCES `demohunt_blog_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_blog_comment_post` FOREIGN KEY (`post_id`) REFERENCES `demohunt_blog_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );
    }

    protected function installNewsTables()
    {
        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_category`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_news_item`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_subject`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_news_item_subject`");

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_category` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',

                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255) DEFAULT NULL,
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',

                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_category_guid` (`guid`),
                    UNIQUE KEY `news_category_pid_slug` (`pid`, `slug`),
                    KEY `news_category_type` (`type`),
                    KEY `news_category_pid` (`pid`),
                    CONSTRAINT `FK_news_category_pid` FOREIGN KEY (`pid`) REFERENCES `demohunt_news_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_news_item` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255) DEFAULT NULL,
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `date` datetime DEFAULT NULL,
                    `content` text,
                    `announcement` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `category_id` bigint(20) unsigned DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_news_item_guid` (`guid`),
                    UNIQUE KEY `news_news_item_slug` (`slug`),
                    KEY `news_news_item_type` (`type`),
                    KEY `news_news_item_category` (`category_id`),
                    CONSTRAINT `FK_news_news_item_category` FOREIGN KEY (`category_id`) REFERENCES `demohunt_news_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_subject` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255) DEFAULT NULL,
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_subject_guid` (`guid`),
                    UNIQUE KEY `news_subject_slug` (`slug`),
                    KEY `news_subject_type` (`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_news_item_subject` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `type` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `news_item_id` bigint(20) unsigned,
                    `subject_id` bigint(20) unsigned,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_news_item_subject_guid` (`guid`),
                    KEY `news_news_item_subject_type` (`type`),
                    KEY `news_news_item_subject_item` (`news_item_id`),
                    KEY `news_news_item_subject_subject` (`subject_id`),
                    CONSTRAINT `FK_news_news_item_subject_item` FOREIGN KEY (`news_item_id`) REFERENCES `demohunt_news_news_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_news_news_item_subject_subject` FOREIGN KEY (`subject_id`) REFERENCES `demohunt_news_subject` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );
    }

    protected function installStructureTable()
    {
        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_structure`");
        $connection->exec(
            "
                CREATE TABLE `demohunt_structure` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255) DEFAULT NULL,
                    `version` int(10) unsigned DEFAULT '1',
                    `type` varchar(255) DEFAULT NULL,
                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255) DEFAULT NULL,
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `content` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `module` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `structure_guid` (`guid`),
                    UNIQUE KEY `structure_mpath` (`mpath`),
                    UNIQUE KEY `structure_pid_slug` (`pid`, `slug`),
                    KEY `structure_parent` (`pid`),
                    KEY `structure_parent_order` (`pid`,`order`),
                    KEY `structure_type` (`type`),
                    CONSTRAINT `FK_structure_parent` FOREIGN KEY (`pid`) REFERENCES `demohunt_structure` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );
    }
}
