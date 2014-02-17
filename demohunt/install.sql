-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: umi
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `umi_blogs_blog`
--

DROP TABLE IF EXISTS `demohunt_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demohunt_structure` (
	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	`guid` varchar(255) DEFAULT NULL,
	`pid` bigint(20) unsigned DEFAULT NULL,
	`mpath` varchar(255) DEFAULT NULL,
	`uri` text,
	`slug` varchar(255) DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`level` int(10) unsigned DEFAULT NULL,
	`version` int(10) unsigned DEFAULT '1',
	`type` varchar(255) DEFAULT NULL,
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
	`child_count` int(10) unsigned DEFAULT '0',
	`module` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `structure_guid` (`guid`),
	UNIQUE KEY `structure_mpath` (`mpath`),
	UNIQUE KEY `structure_pid_slug` (`pid`, `slug`),
	KEY `structure_parent` (`pid`),
	KEY `structure_parent_order` (`pid`,`order`),
	KEY `structure_type` (`type`),
	CONSTRAINT `FK_structure_parent` FOREIGN KEY (`pid`) REFERENCES `demohunt_structure` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demohunt_structure`
--

LOCK TABLES `demohunt_structure` WRITE;
/*!40000 ALTER TABLE `demohunt_structure` DISABLE KEYS */;
INSERT INTO `demohunt_structure` VALUES (2,'d534fd83-0f12-4a0d-9853-583b9181a948',null,'#2','//hunters/ob_otryade','ob_otryade',1,0,2,'structure.static','Об отряде',0,1,NULL,NULL,'<p>Мы &mdash; отряд Охотниц за привидениями. Цвет волос, уровень IQ, размер груди, длина ног и количество высших образований не оказывают существенного влияния при отборе кадров в наши подразделения.</p><p>Единственно значимым критерием является наличие у Охотницы следующих навыков:</p><blockquote>метод десятипальцевой печати;<br /> тайский массаж;<br /> метод левой руки;<br /> техника скорочтения;</blockquote><p>Миссия нашей компании: Спасение людей от привидений во имя спокойствия самих привидений.<br /><br /> 12 лет нашей работы доказали, что предлагаемые нами услуги востребованы человечеством. За это время мы получили:</p><blockquote>1588 искренних благодарностей от клиентов; <br /> 260080 комплиментов; <br /> 5 интересных предложений руки и сердца.</blockquote><p>Нам не только удалось пережить кризис августа 1998 года, но и выйти на новый, рекордный уровень рентабельности.<br /> В своей работе мы используем             <strong>сверхсекретные</strong> супер-пупер-технологии.</p>',NULL,NULL,'Об отряде','Об отряде',1, 'structure');
INSERT INTO `demohunt_structure` VALUES (3,'3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4',2,'#2.3','//hunters/ob_otryade/no','no',1,1,2,'structure.static','Работа, за которую мы никогда не возьмемся',0,1,NULL,NULL,'<ul><li>Безосновательный вызов призраков на дом</li><li>Гадания на картах, кофейной гуще, блюдечке</li><li>Толкование снов</li><li>Интим-услуги. Мы не такие!</li></ul>',NULL,NULL,'Работа, за которую мы никогда не возьмемся','Работа, за которую мы никогда не возьмемся',0, 'structure');
INSERT INTO `demohunt_structure` VALUES (4,'98751ebf-7f76-4edb-8210-c2c3305bd8a0',null,'#4','//hunters/services','services',2,0,2,'structure.static','Услуги',0,1,NULL,NULL,'<p><strong>Дипломатические переговоры с домовыми</strong></p><p>Домовые требуют особого подхода. Выгонять домового из дома категорически запрещено, т.к. его призвание &mdash; охранять дом. Однако, некоторые домовые приносят своим хозяевам немало хлопот из-за своенравного характера. <br /><br />Хорошие отношения с домовым &mdash; наша работы. Правильно провести дипломатические переговоры с домовым, с учетом его знака зодиака, типа температмента и других психографических характеристик, настроить его на позитивный лад, избавить от личных переживаний, разобраться в ваших разногласиях и провести результативные переговоры может грамотный специалист с широким набором характеристик и знаний.<br /><br /><em>Работает Охотница Ольга Карпова <br />Спецнавыки: паранормальная дипломатия, психология поведения духов и разрешение конфликтов</em></p><p><br /><br /><strong>Изгнание призраков царских кровей и других элитных духов<br /></strong><br />Вы купили замок? Хотите провести профилактические работы? Или уже столкнулись с присутствием призраков один на один?<br /><br />Вам &mdash; в наше элитное подразделение. Духи царских кровей отличаются кичливым поведением и высокомерием, однако до сих пор подразделение Охотниц в бикини всегда справлялось с поставленными задачами.<br /><br />Среди наших побед:</p><p>- тень отца Гамлета, вызвавшая переполох в женской раздевалке фитнес-клуба; <br />- призрак Ленина, пытающийся заказать роллы Калифорния на вынос; <br />- призрак Цезаря на неделе миланской моды в Москве.&nbsp; <br /><br /><em>Работает Охотница Елена&nbsp; Жарова <br />Спецнавыки: искусство душевного разговора</em></p>',NULL,NULL,'Услуги','Услуги',0,1),(5,'c81d6d87-25c6-4ab8-b213-ef3a0f044ce6',1,'1.5','//hunters/price','price',3,1,2,'structure.static','Тарифы и цены',0,1,1,0,0,NULL,NULL,'<p><strong>Если вас регулярно посещают привидения, призраки, НЛО, &laquo;Летучий голландец&raquo;, феномен черных рук, демоны, фантомы, вампиры и чупакабры...</strong></p><p>Мы предлагаем вам воспользоваться нашим <strong>тарифом абонентской платы</strong>, который составляет <span style=\"color: #ff6600;\"><strong>1 995</strong></span> у.е. в год. Счастливый год без привидений!</p><p><strong>Если паранормальное явление появился в вашей жизни неожиданно, знакомьтесь с прайсом*:<br /></strong></p><blockquote>Дипломатические переговоры с домовым &ndash; <span style=\"color: #ff6600;\"><strong>120</strong></span> у.е.<br />Нейтрализация вампира &ndash; <span style=\"color: #ff6600;\"><strong>300</strong></span> у.е.<br />Изгнание привидения стандартного &ndash; <span style=\"color: #ff6600;\"><strong>200</strong></span> у.е.<br />Изгнание привидений царей, принцев и принцесс, вождей революций и другой элиты &ndash; <span style=\"color: #ff6600;\"><strong>1250</strong></span> у.е.<br />Борьба с НЛО &ndash; рассчитывается <span style=\"text-decoration: underline;\">индивидуально</span>.</blockquote><p><strong>Специальная услуга: </strong>ВЫЗОВ ОТРЯДА В БИКИНИ</p><p><span style=\"font-size: x-small;\"><em>Стандартные услуги в сочетании с эстетическим удовольствием!</em></span></p><p><strong>Скидки оптовым и постоянным клиентам:</strong><br />При заказе устранения от 5 духов (любого происхождения, включая элиту) предоставляется скидка 12% от общей цены. Скидки по акциям не суммируются.</p><p><span>*Цена за одну особь!</span></p>',NULL,NULL,'Тарифы и цены','Тарифы и цены',0, 'structure');
/*!40000 ALTER TABLE `demohunt_structure` ENABLE KEYS */;
UNLOCK TABLES;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-14 11:17:00