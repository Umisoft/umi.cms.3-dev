<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace demohunt\controller;

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
use umicms\project\module\search\api\SearchApi;
use umicms\project\module\search\api\SearchIndexApi;
use umicms\project\module\structure\object\StaticPage;
use umicms\project\module\structure\object\StructureElement;
use umicms\project\module\users\api\UsersApi;
use umicms\project\module\users\object\User;
use umicms\project\site\controller\SitePageController;

/**
 * Class InstallController
 */
class InstallController extends SitePageController implements ICollectionManagerAware, IObjectPersisterAware
{

    use TCollectionManagerAware;
    use TObjectPersisterAware;

    /**
     * @var IDbCluster $dbCluster
     */
    protected $dbCluster;
    /**
     * @var UsersApi $usersApi
     */
    protected $usersApi;

    /**
     * @var string $testLayout
     */
    protected $testLayout;
    /**
     * @var SearchApi $searchApi
     */
    private $searchIndexApi;

    public function __construct(IDbCluster $dbCluster, UsersApi $usersApi, SearchIndexApi $searchIndexApi)
    {
        $this->dbCluster = $dbCluster;
        $this->usersApi = $usersApi;
        $this->searchIndexApi = $searchIndexApi;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $this->installDbStructure();

        $this->installUsers();
        $this->installStructure();
        $this->installNews();
        $this->installGratitude();
        $this->installBlog();

        $this->getObjectPersister()->commit();
        $this->installSearch();

        return $this->createResponse('Installed');
    }

    protected function installUsers()
    {
        /**
         * @var SimpleCollection $userCollection
         */
        $userCollection = $this->getCollectionManager()->getCollection('user');

        /**
         * @var User $sv
         */
        $sv = $userCollection->add()
            ->setValue('displayName', 'Супервайзер')
            ->setValue('login', 'sv')
            ->setValue('email', 'sv@umisoft.ru')
            ->setGUID('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');

        $this->usersApi->setUserPassword($sv, '1');
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
        $categoriesCollection = $this->getCollectionManager()->getCollection('blogCategory');
        /**
         * @var SimpleCollection $postCollection
         */
        $postCollection = $this->getCollectionManager()->getCollection('blogPost');
        /**
         * @var SimpleHierarchicCollection $commentCollection
         */
        $commentCollection = $this->getCollectionManager()->getCollection('blogComment');


        $structureCollection->add('blog', 'system')
            ->setValue('displayName', 'Блог')
            ->setGUID('9aa6745f-f40d-5489-8043-d959594123ce')
            ->getProperty('componentName')->setValue('blog');

        $blog = $categoriesCollection->add('company')
            ->setValue('displayName', 'Блог')
            ->setValue('metaTitle', 'Блог Охотниц за приведениями')
            ->setValue('h1', 'Блог Охотниц за приведениями')
            ->setValue('contents', '<p>Это блого обо всем на свете...</p>')
            ->setGUID('7865b444-6799-45d6-9145-93a614bdfab7');

        $post1 = $postCollection->add()
            ->setValue('displayName', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('metaTitle', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('h1', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('contents', '<p>Причины девиантного поведения домашних призраков кроются безусловно во влиянии MTV и пропаганде агрессивной альтернативной музыки.<br /><br />Также наблюдается рост домовых, практикующих экстремальное катание на роликовых коньках, скейт-бордах, BMX, что повышает общий уровень черепно-мозговых травм среди паранормальных существ. <br /><br />Не может не оказывать влияния проникновение культуры эмо в быт и уклад домашних призраков, что ведет к росту самоубийств и депрессивных состояний среди этих в общем-то жизнерадостных<br /> созданий.<br /><br />В качестве метода влияния на отклонения у домашний призраков я вижу их обращение в более позитивные и миролюбивые культуры, их пропаганда и популяризация в среде домашних призраков.<br /><br /><strong>Екатерина Джа-Дуплинская</strong></p>')
            ->setValue('slug', 'deviant')
            ->setGUID('8e675484-bea4-4fb5-9802-4750cc21e509');

        $post1->getValue('date')->setTimestamp(strtotime('2010-08-11 17:35:00'));

        /**
         * @var IHierarchicObject $comment1
         */
        $comment1 = $commentCollection->add('comment1')
            ->setValue('displayName', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('contents', '<p>О да. Недавно в нашем замке один милый маленький призрак покончил с собой. Мы были уверены, что это невозможно, но каким-то образом ему удалось раствориться в воде, наполняющей наш древний колодец.</p>')
            ->setValue('post', $post1);
        $comment1->getValue('date')->setTimestamp(strtotime('2012-11-15 15:07:31'));

        $comment2 = $commentCollection->add('comment2', IObjectType::BASE, $comment1)
            ->setValue('displayName', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('contents', '<p>Возможно, вашего призрака еще удастся спасти. Попробуйте насыпать в колодец пару столовых ложек молотых семян бессмертника. Это должно помочь призраку снова сконденсировать свое нематериальное тело. И да, важно, чтобы семена были собраны в новолуние.</p>')
            ->setValue('post', $post1);
        $comment2->getValue('date')->setTimestamp(strtotime('2012-11-15 15:11:21'));

        $post2 = $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('category', $blog)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty_litvinovoj')
            ->setGUID('2ff677ee-765c-42ee-bb97-778f03f00c50');
        $post2->getValue('date')->setTimestamp(strtotime('2010-08-14 17:35:00'));

        $comment3 = $commentCollection->add('comment3')
            ->setValue('displayName', 'важный вопрос')
            ->setValue('contents', '<p>Существует ли разговорник для общения с НЛО? Основы этикета?</p>')
            ->setValue('post', $post2);
        $comment3->getValue('date')->setTimestamp(strtotime('2012-11-15 15:05:34'));

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
        $newsCollection = $this->getCollectionManager()->getCollection('newsItem');
        /**
         * @var SimpleHierarchicCollection $rubricCollection
         */
        $rubricCollection = $this->getCollectionManager()->getCollection('newsRubric');
        /**
         * @var SimpleCollection $subjectCollection
         */
        $subjectCollection = $this->getCollectionManager()->getCollection('newsSubject');

        $subject1 = $subjectCollection->add()
            ->setValue('displayName', 'Призраки')
            ->setValue('slug','prizraki');

        $subject2 = $subjectCollection->add()
            ->setValue('displayName', 'Привидения')
            ->setValue('slug','privideniya');

        $newsPage = $structureCollection->add('novosti', 'system')
            ->setValue('displayName', 'Новости')
            ->setGUID('9ee6745f-f40d-46d8-8043-d959594628ce')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN)
            ->setValue('layout', $this->testLayout);
        $newsPage->getProperty('componentName')->setValue('news');
        $newsPage->getProperty('componentPath')->setValue('news');

        $rubric = $structureCollection->add('rubriki', 'system', $newsPage)
            ->setValue('displayName', 'Новостная рубрика')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462811');

        $rubric->getProperty('componentName')->setValue('rubric');
        $rubric->getProperty('componentPath')->setValue('news.rubric');

        $subject = $structureCollection->add('syuzhety', 'system', $newsPage)
            ->setValue('displayName', 'Новостной сюжет')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462822');

        $subject->getProperty('componentName')->setValue('subject');
        $subject->getProperty('componentPath')->setValue('news.subject');

        $item = $structureCollection->add('item', 'system', $newsPage)
            ->setValue('displayName', 'Новость')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462833');

        $item->getProperty('componentName')->setValue('item');
        $item->getProperty('componentPath')->setValue('news.item');

        $rubric = $rubricCollection->add('company')
            ->setValue('displayName', 'Новости сайта')
            ->setValue('metaTitle', 'Новости сайта')
            ->setValue('h1', 'Новости сайта')
            ->setGUID('8650706f-04ca-49b6-a93d-966a42377a61');

        $sport = $rubricCollection->add('sport')
            ->setValue('displayName', 'Новости спорта')
            ->setValue('metaTitle', 'Новости спорта')
            ->setValue('h1', 'Новости спорта');

        $winterSports = $rubricCollection->add('winter', IObjectType::BASE, $sport)
            ->setValue('displayName', 'Зимний спорт')
            ->setValue('metaTitle', 'Зимний спорт')
            ->setValue('h1', 'Зимний спорт');

        $summerSports = $rubricCollection->add('summer', IObjectType::BASE, $sport)
            ->setValue('displayName', 'Летний спорт')
            ->setValue('metaTitle', 'Летний спорт')
            ->setValue('h1', 'Летний спорт');

        $snowboard = $rubricCollection->add('snowboard', IObjectType::BASE, $winterSports)
            ->setValue('displayName', 'Сноуборд')
            ->setValue('metaTitle', 'Сноуборд')
            ->setValue('h1', 'Сноуборд');

        $ski = $rubricCollection->add('ski', IObjectType::BASE, $winterSports)
            ->setValue('displayName', 'Лыжи')
            ->setValue('metaTitle', 'Лыжи')
            ->setValue('h1', 'Лыжи');

        $item = $newsCollection->add()
            ->setValue('displayName', 'Российские биатлонисты взяли первые три места')
            ->setValue('metaTitle', 'Российские биатлонисты взяли первые три места')
            ->setValue('h1', 'Российские биатлонисты взяли первые три места')
            ->setValue('announcement', '<p>Чудо на олимпиаде в Сочи</p>')
            ->setValue('contents', '<p>На олимпиаде в Сочи российские биатлонисты взяли все медали.</p>')
            ->setValue('rubric', $ski)
            ->setValue('slug', 'biathlon');

        $volleyball = $rubricCollection->add('volleyball', IObjectType::BASE, $summerSports)
            ->setValue('displayName', 'Волейбол')
            ->setValue('metaTitle', 'Волейбол')
            ->setValue('h1', 'Волейбол');

        $item = $newsCollection->add()
            ->setValue('displayName', 'Названа причина социопатии современных зомби')
            ->setValue('metaTitle', 'Названа причина социопатии современных зомби')
            ->setValue('h1', 'Названа причина социопатии современных зомби')
            ->setValue('announcement', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.</p>')
            ->setValue('contents', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.  Ученые давно бьют тревогу по поводу образа жизни молодых зомби и сейчас активно занялись пропагандой спорта, фитнес-клубов, активных игр на воздухе и популяризацией вегетарианской пищи среди представителей этого вида.  Пока ученые занимаются всеми этими вещами, молодые зомби курят по подъездам, впадают в депрессивные состоянии, примыкают к эмо-группировкам и совершенно не хотят работать.  &laquo;А между тем, этих ребят еще можно спасти, &mdash; комментирует Виктория Евдокимова, Охотница за привидениями со стажем, &mdash; и это в силах каждого из нас. Если увидите на улице одинокого зомби, подойдите и поинтересуйтесь, как обстоят дела с его девчонкой, какие у него планы на выходные, и что он делал прошлым летом&raquo;.</p>')
            ->setValue('rubric', $rubric)
            ->setGUID('d6eb9ad1-667e-429d-a476-fa64c5eec115')
            ->setValue('slug', 'zombi');

        $item->getValue('date')->setTimestamp(strtotime('2010-08-01 17:34:00'));

        $subjects = $item->getValue('subjects');
        $subjects->attach($subject1);
        $subjects->attach($subject2);

        $newsCollection->add()
            ->setValue('displayName', 'Смена состава в Отряде в бикини')
            ->setValue('metaTitle', 'Смена состава в Отряде в бикини')
            ->setValue('h1', 'Смена состава в Отряде в бикини')
            ->setValue('announcement', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.</p>')
            ->setValue('contents', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.  Маша Шикова имеет большой опыт в борьбе с домашними призраками и два столкновения с вампирами. Новая Охотница прекрасно вписалась в наш дружный женский коллектив и в ожидании интересных заданий уже пополнила свой гардероб пятью новыми комплектами бикини.   Лолита Андреева на редкость вяло комментирует свой выход из отряда. По нашим данным, это связано с тем, что маникюрный мастер девушки, с которым у нее был длительный роман, без предупреждения уехал в отпуск на Бали и оставил ее "подыхать в одиночестве".</p>')
            ->setValue('rubric', $rubric)
            ->setGUID('35806ed8-1306-41b5-bbf9-fe2faedfc835')
            ->setValue('slug', 'bikini')
            ->getValue('date')->setTimestamp(strtotime('2010-08-03 17:36:00'));

        foreach (range(10, 50) as $num) {
            $newsCollection->add()
                ->setValue('displayName', 'Открыт метод устранения неврозов у привидений-'.$num)
                ->setValue('metaTitle', 'Открыт метод устранения неврозов у привидений')
                ->setValue('h1', 'Открыт метод устранения неврозов у привидений-'.$num)
                ->setValue('announcement', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина<br />Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим<br />средством воздействия на привидения были, есть и будут красивые женские<br />ноги.</p>')
                ->setValue('contents', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим средством воздействия на привидения были, есть и будут красивые женские ноги.  &laquo;Я долго шла к этому открытию, и на пути к нему совершила много других маленьких открытий, однако лучшее практическое применение получили именно мои ноги&raquo;, &mdash; рассказывает первооткрывательница.  В своем масштабном научном труде она дает рекомендации по правильному применению метода среди призраков и людей, а также эффективной длине юбке и оптимальной высоте каблука.</p>')
                ->setValue('rubric', $rubric)
                ->setValue('slug', 'privideniya-'.$num)
                ->getValue('date')->setTimestamp(strtotime('2010-08-02 17:35:00'));
        }


    }

    protected function installGratitude() {

        /**
         * @var SimpleCollection $newsCollection
         */
        $newsCollection = $this->getCollectionManager()->getCollection('newsItem');
        /**
         * @var SimpleHierarchicCollection $rubricCollection
         */
        $rubricCollection = $this->getCollectionManager()->getCollection('newsRubric');

        $gratitude = $rubricCollection->add('gratitude')
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
            ->setValue('contents', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло. Я вызвала наряд охотниц за привидениями, и теперь мы избавлены от этих проблем. Сотрудница организации рекомендовала мне воспользоваться услугами спецподразделения &laquo;Отряд в бикини&raquo;. Я не пожалела, и, кажется, муж остался доволен.</p>')
            ->setValue('rubric', $gratitude)
            ->setValue('slug', 'natasha')
            ->setGUID('da5ec9a8-229c-4120-949c-2bb9eb641f24')
            ->getValue('date')->setTimestamp(strtotime('2013-06-24 19:11'));

        $newsCollection->add()
            ->setValue('displayName', 'Александр')
            ->setValue('metaTitle', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('h1', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('announcement', '<p>С 18 лет меня довольно регулярно похищали инопланетяне.&nbsp;Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде</p>')
            ->setValue('contents', '<p>С 18 лет меня довольно регулярно похищали инопланетяне. Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде &ndash; я перестал смыслить свою жизнь без пива и чипсов. Я был вынужден обратиться к профессионалам. Как мне помогли Охотницы? Инициировав повторный сеанс связи, они совершили настоящий переворот. Теперь я замечательно обхожусь пряниками и шоколадом. Особую благодарность хочу выразить Охотнице Елене Жаровой за красивые глаза.</p>')
            ->setValue('rubric', $gratitude)
            ->setGUID('60744128-996a-4cea-a937-c20ebc5c8c77')
            ->setValue('slug', 'aleksandr')
            ->getValue('date')->setTimestamp(strtotime('2013-06-24 19:14'));

    }

    protected function installStructure()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');


        $parent = null;
        for ($i = 0; $i < 20; $i++) {
            $parent = $structureCollection->add('item' . $i, 'static', $parent)
                ->setValue('displayName', 'item' . $i);
        }

        /**
         * @var SimpleCollection $structureCollection
         */
        $layoutCollection = $this->getCollectionManager()->getCollection('layout');

        $layoutCollection->add()
            ->setValue('fileName', 'layout')
            ->setValue('displayName', 'Основной')
            ->setGUID('d6cb8b38-7e2d-4b36-8d15-9fe8947d66c7');

        $this->testLayout = $layoutCollection->add()
            ->setValue('fileName', 'test')
            ->setValue('displayName', 'Тестовый');

        $structurePage = $structureCollection->add('structure', 'system')
            ->setValue('displayName', 'Структура');
        $structurePage->getProperty('componentName')->setValue('structure');
        $structurePage->getProperty('componentPath')->setValue('structure');

        $menuPage = $structureCollection->add('menu', 'system', $structurePage)
            ->setValue('displayName', 'Меню');
        $menuPage->getProperty('componentName')->setValue('menu');
        $menuPage->getProperty('componentPath')->setValue('structure.menu');

        /**
         * @var StaticPage $about
         */
        $about = $structureCollection->add('ob_otryade', 'static')
            ->setValue('displayName', 'Об отряде')
            ->setValue('metaTitle', 'Об отряде')
            ->setValue('h1', 'Об отряде')
            ->setValue('contents', '<p>Мы &mdash; отряд Охотниц за привидениями. Цвет волос, уровень IQ, размер груди, длина ног и количество высших образований не оказывают существенного влияния при отборе кадров в наши подразделения.</p><p>Единственно значимым критерием является наличие у Охотницы следующих навыков:</p><blockquote>метод десятипальцевой печати;<br /> тайский массаж;<br /> метод левой руки;<br /> техника скорочтения;</blockquote><p>Миссия нашей компании: Спасение людей от привидений во имя спокойствия самих привидений.<br /><br /> 12 лет нашей работы доказали, что предлагаемые нами услуги востребованы человечеством. За это время мы получили:</p><blockquote>1588 искренних благодарностей от клиентов; <br /> 260080 комплиментов; <br /> 5 интересных предложений руки и сердца.</blockquote><p>Нам не только удалось пережить кризис августа 1998 года, но и выйти на новый, рекордный уровень рентабельности.<br /> В своей работе мы используем             <strong>сверхсекретные</strong> супер-пупер-технологии.</p>')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_CURRENT_SHOWN)
            ->setGUID('d534fd83-0f12-4a0d-9853-583b9181a948');

        $about->getProperty('componentName')->setValue('structure');
        $about->getProperty('componentPath')->setValue('structure');

        $no = $structureCollection->add('no', 'static', $about)
            ->setValue('displayName', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('metaTitle', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('h1', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('contents', '<ul><li>Безосновательный вызов призраков на дом</li><li>Гадания на картах, кофейной гуще, блюдечке</li><li>Толкование снов</li><li>Интим-услуги. Мы не такие!</li></ul>')
            ->setGUID('3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4');
        $no->getProperty('componentName')->setValue('structure');
        $no->getProperty('componentPath')->setValue('structure');


        $service = $structureCollection->add('services', 'static')
            ->setValue('displayName', 'Услуги')
            ->setValue('metaTitle', 'Услуги')
            ->setValue('h1', 'Услуги')
            ->setValue('contents', '<p><strong>Дипломатические переговоры с домовыми</strong></p><p>Домовые требуют особого подхода. Выгонять домового из дома категорически запрещено, т.к. его призвание &mdash; охранять дом. Однако, некоторые домовые приносят своим хозяевам немало хлопот из-за своенравного характера. <br /><br />Хорошие отношения с домовым &mdash; наша работы. Правильно провести дипломатические переговоры с домовым, с учетом его знака зодиака, типа температмента и других психографических характеристик, настроить его на позитивный лад, избавить от личных переживаний, разобраться в ваших разногласиях и провести результативные переговоры может грамотный специалист с широким набором характеристик и знаний.<br /><br /><em>Работает Охотница Ольга Карпова <br />Спецнавыки: паранормальная дипломатия, психология поведения духов и разрешение конфликтов</em></p><p><br /><br /><strong>Изгнание призраков царских кровей и других элитных духов<br /></strong><br />Вы купили замок? Хотите провести профилактические работы? Или уже столкнулись с присутствием призраков один на один?<br /><br />Вам &mdash; в наше элитное подразделение. Духи царских кровей отличаются кичливым поведением и высокомерием, однако до сих пор подразделение Охотниц в бикини всегда справлялось с поставленными задачами.<br /><br />Среди наших побед:</p><p>- тень отца Гамлета, вызвавшая переполох в женской раздевалке фитнес-клуба; <br />- призрак Ленина, пытающийся заказать роллы Калифорния на вынос; <br />- призрак Цезаря на неделе миланской моды в Москве.&nbsp; <br /><br /><em>Работает Охотница Елена&nbsp; Жарова <br />Спецнавыки: искусство душевного разговора</em></p>')
            ->setGUID('98751ebf-7f76-4edb-8210-c2c3305bd8a0');
        $service->getProperty('componentName')->setValue('structure');
        $service->getProperty('componentPath')->setValue('structure');

        $price = $structureCollection->add('price', 'static')
            ->setValue('displayName', 'Тарифы и цены')
            ->setValue('metaTitle', 'Тарифы и цены')
            ->setValue('h1', 'Тарифы и цены')
            ->setValue('contents', '<p><strong>Если вас регулярно посещают привидения, призраки, НЛО, &laquo;Летучий голландец&raquo;, феномен черных рук, демоны, фантомы, вампиры и чупакабры...</strong></p><p>Мы предлагаем вам воспользоваться нашим <strong>тарифом абонентской платы</strong>, который составляет <span style="color: #ff6600;"><strong>1 995</strong></span> у.е. в год. Счастливый год без привидений!</p><p><strong>Если паранормальное явление появился в вашей жизни неожиданно, знакомьтесь с прайсом*:<br /></strong></p><blockquote>Дипломатические переговоры с домовым &ndash; <span style="color: #ff6600;"><strong>120</strong></span> у.е.<br />Нейтрализация вампира &ndash; <span style="color: #ff6600;"><strong>300</strong></span> у.е.<br />Изгнание привидения стандартного &ndash; <span style="color: #ff6600;"><strong>200</strong></span> у.е.<br />Изгнание привидений царей, принцев и принцесс, вождей революций и другой элиты &ndash; <span style="color: #ff6600;"><strong>1250</strong></span> у.е.<br />Борьба с НЛО &ndash; рассчитывается <span style="text-decoration: underline;">индивидуально</span>.</blockquote><p><strong>Специальная услуга: </strong>ВЫЗОВ ОТРЯДА В БИКИНИ</p><p><span style="font-size: x-small;"><em>Стандартные услуги в сочетании с эстетическим удовольствием!</em></span></p><p><strong>Скидки оптовым и постоянным клиентам:</strong><br />При заказе устранения от 5 духов (любого происхождения, включая элиту) предоставляется скидка 12% от общей цены. Скидки по акциям не суммируются.</p><p><span>*Цена за одну особь!</span></p>')
            ->setGUID('c81d6d87-25c6-4ab8-b213-ef3a0f044ce6');
        $price->getProperty('componentName')->setValue('structure');
        $price->getProperty('componentPath')->setValue('structure');


        $menuItem1 = $structureCollection->add('menu_item_1', 'static')
            ->setValue('displayName', 'Menu Item 1')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem1->getProperty('componentName')->setValue('structure');
        $menuItem1->getProperty('componentPath')->setValue('structure');

        $menuItem11 = $structureCollection->add('menu_item_1_1', 'static', $menuItem1)
            ->setValue('displayName', 'Menu Item 1.1')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem11->getProperty('componentName')->setValue('structure');
        $menuItem11->getProperty('componentPath')->setValue('structure');

        $menuItem12 = $structureCollection->add('menu_item_1_2', 'static', $menuItem1)
            ->setValue('displayName', 'Menu Item 1.2')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem12->getProperty('componentName')->setValue('structure');
        $menuItem12->getProperty('componentPath')->setValue('structure');

        $menuItem121 = $structureCollection->add('menu_item_1_2_1', 'static', $menuItem12)
            ->setValue('displayName', 'Menu Item 1.2.1')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem121->getProperty('componentName')->setValue('structure');
        $menuItem121->getProperty('componentPath')->setValue('structure');

        $menuItem122 = $structureCollection->add('menu_item_1_2_2', 'static', $menuItem12)
            ->setValue('displayName', 'Menu Item 1.2.2')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem122->getProperty('componentName')->setValue('structure');
        $menuItem122->getProperty('componentPath')->setValue('structure');

        $menuItem1221 = $structureCollection->add('menu_item_1_2_2_1', 'static', $menuItem122)
            ->setValue('displayName', 'Menu Item 1.2.2.1')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem1221->getProperty('componentName')->setValue('structure');
        $menuItem1221->getProperty('componentPath')->setValue('structure');

    }

    protected function installDbStructure()
    {
        $connection = $this->dbCluster->getConnection();
        /**
         * @var IDialect $dialect
         */
        $dialect = $connection->getDatabasePlatform();
        $connection->exec($dialect->getDisableForeignKeysSQL());

        $this->installStructureTables();
        $this->installNewsTables();
        $this->installBlogTables();

        $this->installUsersTables();

        $connection->exec($dialect->getEnableForeignKeysSQL());
    }

    protected function installUsersTables()
    {
        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_user`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_user_group`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_user_user_group`");

        $connection->exec(
            "
                CREATE TABLE `demohunt_user` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `login` varchar(255) DEFAULT NULL,
                    `email` varchar(255) DEFAULT NULL,
                    `password` varchar(255) DEFAULT NULL,
                    `password_salt` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_guid` (`guid`),
                    KEY `user_type` (`type`),
                    UNIQUE KEY `user_login` (`login`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_user_group` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    PRIMARY KEY (`id`),
                    UNIQUE KEY `group_guid` (`guid`),
                    KEY `group_type` (`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_user_user_group` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `user_id` bigint(20) unsigned,
                    `user_group_id` bigint(20) unsigned,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_user_group_guid` (`guid`),
                    KEY `user_user_group_type` (`type`),
                    KEY `user_user_group_user` (`user_id`),
                    KEY `user_user_group_group` (`user_group_id`),
                    CONSTRAINT `FK_user_user_group_user` FOREIGN KEY (`user_id`) REFERENCES `demohunt_user_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_user_user_group_group` FOREIGN KEY (`user_group_id`) REFERENCES `demohunt_user_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );
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
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',

                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255),
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',

                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
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
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255),
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `date` datetime DEFAULT NULL,
                    `contents` text,
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
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255),
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
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
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

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
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255),
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
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

        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_rubric`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_news_item`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_subject`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_news_news_item_subject`");

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_rubric` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',

                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255),
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',

                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `layout_id` bigint(20) unsigned DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_rubric_guid` (`guid`),
                    UNIQUE KEY `news_rubric_pid_slug` (`pid`, `slug`),
                    KEY `news_rubric_type` (`type`),
                    KEY `news_rubric_pid` (`pid`),
                    KEY `news_rubric_layout` (`layout_id`),
                    CONSTRAINT `FK_news_rubric_pid` FOREIGN KEY (`pid`) REFERENCES `demohunt_news_rubric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_news_rubric_layout` FOREIGN KEY (`layout_id`) REFERENCES `demohunt_layout` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_news_item` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255),
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `date` datetime DEFAULT NULL,
                    `contents` text,
                    `announcement` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `rubric_id` bigint(20) unsigned DEFAULT NULL,
                    `layout_id` bigint(20) unsigned DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_news_item_guid` (`guid`),
                    UNIQUE KEY `news_news_item_slug` (`slug`),
                    KEY `news_news_item_type` (`type`),
                    KEY `news_news_item_rubric` (`rubric_id`),
                    KEY `news_news_item_layout` (`layout_id`),
                    CONSTRAINT `FK_news_news_item_rubric` FOREIGN KEY (`rubric_id`) REFERENCES `demohunt_news_rubric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_news_news_item_layout` FOREIGN KEY (`layout_id`) REFERENCES `demohunt_layout` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_subject` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `slug` varchar(255),
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `layout_id` bigint(20) unsigned DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_subject_guid` (`guid`),
                    UNIQUE KEY `news_subject_slug` (`slug`),
                    KEY `news_subject_type` (`type`),
                    KEY `news_subject_layout` (`layout_id`),
                    CONSTRAINT `FK_news_subject_layout` FOREIGN KEY (`layout_id`) REFERENCES `demohunt_layout` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );

        $connection->exec(
            "
                CREATE TABLE `demohunt_news_news_item_subject` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `type` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

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

    protected function installStructureTables()
    {
        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_structure`");
        $connection->exec("DROP TABLE IF EXISTS `demohunt_layout`");

        $connection->exec(
            "
                CREATE TABLE `demohunt_layout` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `type` varchar(255),
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `file_name` varchar(255) DEFAULT NULL,

                    PRIMARY KEY (`id`),
                    UNIQUE KEY `layout_guid` (`guid`),
                    KEY `layout_type` (`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );


        $connection->exec(
            "
                CREATE TABLE `demohunt_structure` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `guid` varchar(255),
                    `version` int(10) unsigned DEFAULT '1',
                    `type` varchar(255),
                    `pid` bigint(20) unsigned DEFAULT NULL,
                    `mpath` varchar(255) DEFAULT NULL,
                    `uri` text,
                    `slug` varchar(255),
                    `order` int(10) unsigned DEFAULT NULL,
                    `level` int(10) unsigned DEFAULT NULL,
                    `child_count` int(10) unsigned DEFAULT '0',
                    `display_name` varchar(255) DEFAULT NULL,
                    `locked` tinyint(1) unsigned DEFAULT '0',
                    `trashed` tinyint(1) unsigned DEFAULT '0',
                    `active` tinyint(1) unsigned DEFAULT '1',
                    `created` datetime DEFAULT NULL,
                    `updated` datetime DEFAULT NULL,
                    `owner_id` bigint(20) unsigned DEFAULT NULL,
                    `editor_id` bigint(20) unsigned DEFAULT NULL,
                    `permissions` int(10) unsigned DEFAULT 0,

                    `contents` text,
                    `meta_description` varchar(255) DEFAULT NULL,
                    `meta_keywords` varchar(255) DEFAULT NULL,
                    `meta_title` varchar(255) DEFAULT NULL,
                    `h1` varchar(255) DEFAULT NULL,
                    `component_path` varchar(255) DEFAULT NULL,
                    `component_name` varchar(255) DEFAULT NULL,
                    `layout_id` bigint(20) unsigned DEFAULT NULL,
                    `in_menu` tinyint(1) unsigned DEFAULT 0,
                    `submenu_state` tinyint(1) unsigned DEFAULT 0,

                    PRIMARY KEY (`id`),
                    UNIQUE KEY `structure_guid` (`guid`),
                    UNIQUE KEY `structure_mpath` (`mpath`),
                    UNIQUE KEY `structure_pid_slug` (`pid`, `slug`),
                    KEY `structure_parent` (`pid`),
                    KEY `structure_parent_order` (`pid`,`order`),
                    KEY `structure_type` (`type`),
                    KEY `structure_layout` (`layout_id`),
                    KEY `component_path` (`component_path`),
                    KEY `component_name` (`component_name`),
                    CONSTRAINT `FK_structure_parent` FOREIGN KEY (`pid`) REFERENCES `demohunt_structure` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `FK_structure_layout` FOREIGN KEY (`layout_id`) REFERENCES `demohunt_layout` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            "
        );
    }

    private function installSearch()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        $searchRoot = $structureCollection->add('search', 'system');
        $searchRoot->setValue('displayName', 'Поиск')
            ->setGUID('9ee6745f-f40d-46d8-8043-d901234628ce');
        $searchRoot->getProperty('componentName')->setValue('search');
        $searchRoot->getProperty('componentPath')->setValue('search');

        $connection = $this->dbCluster->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `demohunt_search_index`");

        $connection->exec(
            "CREATE TABLE `demohunt_search_index` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `guid` varchar(255),
                `version` int(10) unsigned DEFAULT '1',
                `type` varchar(255),
                `display_name` varchar(255) DEFAULT NULL,
                `locked` tinyint(1) unsigned DEFAULT '0',
                `active` tinyint(1) unsigned DEFAULT '1',
                `created` datetime DEFAULT NULL,
                `updated` datetime DEFAULT NULL,
                `owner_id` bigint(20) unsigned DEFAULT NULL,
                `editor_id` bigint(20) unsigned DEFAULT NULL,
                `permissions` int(10) unsigned DEFAULT 0,

                `date_indexed` datetime DEFAULT NULL,
                `collection_id` varchar(255) DEFAULT NULL,
                `ref_guid` varchar(255) DEFAULT NULL,
                `contents` TEXT DEFAULT NULL,

                PRIMARY KEY (`id`),
                UNIQUE KEY `search_index_guid` (`guid`),
                UNIQUE KEY `search_index_ref_guid` (`ref_guid`),
                KEY `search_index_type` (`type`),
                KEY `search_index_collection_id` (`collection_id`),
                FULLTEXT(`contents`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8
            "
        );

//        $this->searchIndexApi->buildIndex('structure');
        $this->searchIndexApi->buildIndex('newsRubric');
        $this->searchIndexApi->buildIndex('newsItem');
        $this->searchIndexApi->buildIndex('newsSubject');
        $this->searchIndexApi->buildIndex('blogCategory');
        $this->searchIndexApi->buildIndex('blogPost');
        $this->searchIndexApi->buildIndex('blogComment');
        $this->getObjectPersister()->commit();
    }
}
