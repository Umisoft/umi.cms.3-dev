<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:output
            encoding="utf-8"
            method="html"
            indent="yes"
            cdata-section-elements="script noscript"
            omit-xml-declaration="yes"
            doctype-system="about:legacy-compat"
            />

    <xsl:include href="template://module/structure/menu" />
    <xsl:include href="template://module/structure/infoblock" />

    <xsl:template match="/layout">
        <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
        <textarea>
            <xsl:copy-of select="."/>
        </textarea>
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
                <meta name="Description" content="{description}" />
                <meta name="Keywords" content="{keywords}" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title><xsl:value-of select="title" /></title>
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/reset.css" />
                <link rel="stylesheet" href="/resources/umi-rockband/css/bootstrap.css" type="text/css" />
                <link rel="stylesheet" href="/resources/umi-rockband/css/bootstrap-theme.css" type="text/css" />
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/style.css" />
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/bootstrap-class.css" />
                <link rel="shortcut icon" href="/resources/umi-rockband/images/favicon.ico" />
                <script src="/resources/umi-rockband/js/jquery-1.11.0.min.js"></script>
                <script type="text/javascript" src="/resources/umi-rockband/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="/resources/umi-rockband/js/scripts.js"></script>
            </head>

            <body>
                <header id="top">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 col-md-2">
                                <ul class="langs">
                                    <xsl:apply-templates select="locales" />
                                </ul>
                                <br />
                                <a href="" class="logo"><img src="/resources/umi-rockband/images/logo.png" /></a>
                            </div>
                            <div class="right col-xs-12 col-sm-9 col-md-10 text-right">
                                <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="phone"/>
                                <br />
                                <xsl:apply-templates select="document('widget://structure.menu.auto?depth=1')" mode="headerMenu"/>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="header-height"></div>

                <xsl:apply-templates select="contents" mode="content"/>




                <div class="content-main">
                    <div class="container-fluid">
                        <div class="block">
                            <h2 class="title-block"><span>Последние события</span></h2>
                            <p class="desc-block">Свежие новости за последние 24 часа</p>
                            <ul class="list-dev row">
                                <li class="col-md-3 col-sm-6" >
                                    <a href="#"><img src="/resources/umi-rockband/images/list-dev/1.jpg" class="img" alt="" /></a><br />
                                    <a href="#" class="title">Ученые из ЕС создали возобновляемый керосин</a>
                                    <span class="date">May 6, 2014 at 7:53am</span>
                                    <p class="desc">Ученым из проекта Solar-jet удалось в лабораторных условиях воссоздать небольшое количество топлива из продуктов его...</p>
                                </li>
                                <li class="col-md-3 col-sm-6">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-dev/2.jpg" class="img" alt="" /></a><br />
                                    <a href="#" class="title">Ученые из ЕС создали возобновляемый керосин</a>
                                    <span class="date">May 6, 2014 at 7:53am</span>
                                    <p class="desc">Ученым из проекта Solar-jet удалось в лабораторных условиях воссоздать небольшое количество топлива из продуктов его...</p>
                                </li>
                                <li class="col-md-3 col-sm-6">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-dev/3.jpg" class="img" alt="" /></a><br />
                                    <a href="#" class="title">Ученые из ЕС создали возобновляемый керосин</a>
                                    <span class="date">May 6, 2014 at 7:53am</span>
                                    <p class="desc">Ученым из проекта Solar-jet удалось в лабораторных условиях воссоздать небольшое количество топлива из продуктов его...</p>
                                </li>
                                <li class="col-md-3 col-sm-6">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-dev/4.jpg" class="img" alt=""/></a><br />
                                    <a href="#" class="title">Ученые из ЕС создали возобновляемый керосин</a>
                                    <span class="date">May 6, 2014 at 7:53am</span>
                                    <p class="desc">Ученым из проекта Solar-jet удалось в лабораторных условиях воссоздать небольшое количество топлива из продуктов его...</p>
                                </li>
                                <li class="col-md-3 col-sm-6">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-dev/5.jpg" class="img" alt="" /></a><br />
                                    <a href="#" class="title">Ученые из ЕС создали возобновляемый керосин</a>
                                    <span class="date">May 6, 2014 at 7:53am</span>
                                    <p class="desc">Ученым из проекта Solar-jet удалось в лабораторных условиях воссоздать небольшое количество топлива из продуктов его...</p>
                                </li>
                            </ul>
                            <div class="text-center"><a href="#" class="btn btn-custom btn-primary" style="width:240px">Загрузить еще</a></div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="container-fluid text-center">
                            <h2 class="title-block"><span>О нас</span></h2>
                            <p class="desc-block">Мы самая известная рок группа в мире</p>
                        </div>
                        <ul class="list-about">
                            <li><img src="/resources/umi-rockband/images/about/1.jpg" alt="" /></li>
                            <li><img src="/resources/umi-rockband/images/about/2.jpg" alt="" /></li>
                            <li><img src="/resources/umi-rockband/images/about/3.jpg" alt="" /></li>
                            <li><img src="/resources/umi-rockband/images/about/4.jpg" alt="" /></li>
                            <li><img src="/resources/umi-rockband/images/about/5.jpg" alt="" /></li>
                        </ul>
                    </div>
                    <div class="container-fluid content-page">
                        <h3>Невероятно крутой заголовок</h3>
                        <p>Товарищи! постоянный количественный рост и сфера нашей активности требуют определения и уточнения новых предложений. Повседневная практика показывает, что постоянный количественный рост и сфера нашей активности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. Товарищи! сложившаяся структура организации обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Повседневная практика показывает, что начало повседневной работы по формированию позиции представляет собой интересный эксперимент проверки соответствующий условий активизации. </p>
                        <br />
                        <h3>Дополнительный заголовок</h3>
                        <p>Сфера нашей активности в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. Товарищи! сложившаяся структура организации обеспечивает широкому кругу (специалистов) участие в формировании форм развития. </p>
                        <p>Повседневная практика показывает, что начало повседневной работы по формированию позиции представляет собой интересный эксперимент проверки соответствующий условий активизации. Товарищи! укрепление и развитие структуры обеспечивает широкому кругу (специалистов) участие в формировании новых предложений. Не следует, однако забывать, что сложившаяся структура организации в значительной степени обуславливает создание существенных финансовых и административных условий.</p>
                    </div>
                    <div class="block">
                        <h2 class="title-block"><span>Блог</span></h2>
                        <p class="desc-block">Последние статьи из блога</p>
                    </div>
                    <div class="main-blog">
                        <div class="container-fluid">
                            <div class="row list-blog">
                                <div class="item col-md-4 blue col-sm-5 col-xs-12">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-blog/1.jpg" class="img" alt="" /></a>
                                    <div class="bottom">
                                        <h4><a href="#" class="title">Значение новых предложений</a></h4>
                                        <p class="desc">Повседневная практика показывает, что новая модель организационной деятельности позволяет оценить значение новых предложений. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет выполнять важные задания по разработке дальнейших направлений развития. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет </p>
                                        <ul class="info">
                                            <li class="date">17 мая 2014</li>
                                            <li class="cat">Музыка  |  22 комментария</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="item col-md-4 yellow col-sm-5 col-xs-12">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-blog/2.jpg" class="img" alt="" /></a>
                                    <div class="bottom">
                                        <h4><a href="#" class="title">Значение новых предложений</a></h4>
                                        <p class="desc">Повседневная практика показывает, что новая модель организационной деятельности позволяет оценить значение новых предложений. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет выполнять важные задания по разработке дальнейших направлений развития. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет </p>
                                        <ul class="info">
                                            <li class="date">17 мая 2014</li>
                                            <li class="cat">Музыка  |  22 комментария</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="item col-md-4 blue-light col-sm-5 col-xs-12">
                                    <a href="#"><img src="/resources/umi-rockband/images/list-blog/3.jpg" class="img" alt="" /></a>
                                    <div class="bottom">
                                        <h4><a href="#" class="title">Значение новых предложений</a></h4>
                                        <p class="desc">Повседневная практика показывает, что новая модель организационной деятельности позволяет оценить значение новых предложений. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет выполнять важные задания по разработке дальнейших направлений развития. Задача организации, в особенности же начало повседневной работы по формированию позиции позволяет </p>
                                        <ul class="info">
                                            <li class="date">17 мая 2014</li>
                                            <li class="cat">Музыка  |  22 комментария</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center"><a href="#" class="btn btn-custom btn-primary" style="width:240px">Загрузить еще</a></div>
                        </div>
                    </div>
                </div>
                <footer>
                    <div class="container-fluid">
                        <div class="row">
                            <xsl:apply-templates select="document('widget://structure.menu.auto?depth=2')" mode="footerMenu"/>

                            <div class="col-md-12 text-center">
                                <ul class="social">
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/facebook.png" alt="" /></a></li>
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/twitter.png" alt="" /></a></li>
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/google.png" alt="" /></a></li>
                                </ul>
                                <p class="copy">
                                    <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="copyright"/>
                                </p>
                                <a href="#top" class="top-scroll"><img src="/resources/umi-rockband/images/top-scroll.png" alt=""/></a>
                            </div>
                        </div>
                    </div>
                </footer>
            </body>
        </html>

    </xsl:template>
    <!-- Языки в хедере <Начало> -->
    <xsl:template match="locales">
        <ul class="langs">
            <xsl:apply-templates select="locale" />
        </ul>
    </xsl:template>

    <xsl:template match="locale">
        <li><a href="{@url}">
            <xsl:value-of select="@id"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[@current = '1']">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="@id"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[position() = last()]">
        <li><a href="{@url}">
            <xsl:value-of select="@id"/>
        </a></li>
    </xsl:template>

    <xsl:template match="locale[@current = '1' and position() = last()]">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="@id"/>
        </a></li>
    </xsl:template>
    <!-- Языки в хедере <Конец> -->

    <!-- Телефон в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="phone">
        <span class="phone">
            <xsl:value-of select="infoBlock/property[@name = 'phoneNumber']/value" disable-output-escaping="yes"/>
        </span>
    </xsl:template>
    <!-- Телефон в хедере <Конец> -->

    <!-- Меню в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="headerMenu">
        <ul class="menu">
            <xsl:apply-templates select="menu/item" mode="headerMenu"/>
            <li class="search">
                <form class="h-search-form">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><img src="/resources/umi-rockband/images/search-icon.png" width="12" height="12" /></button>
                        </span>
                        <input type="text" class="form-control" />
                    </div>
                </form>
            </li>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="headerMenu">
        <li><a href="{page/@url}">
            <xsl:value-of select="page/@displayName"/>
        </a></li>
    </xsl:template>
    <!-- Меню в хедере <Конец> -->

    <!-- Слайдер на главной <Начало> -->
    <xsl:template name="mainSlider">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="/resources/umi-rockband/images/slider-img.jpg" alt="" />
                    <div class="carousel-caption">
                        <h1>UMI ROCK THEME</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat </p>
                        <a href="#" class="btn btn-custom btn-warning">Подробнее</a>
                    </div>
                </div>
                <div class="item">
                    <img src="/resources/umi-rockband/images/slider-img.jpg" alt="" />
                    <div class="carousel-caption">
                        <h1>UMI ROCK THEME</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat </p>
                        <a href="#" class="btn btn-custom btn-warning">Подробнее</a>
                    </div>
                </div>
                <div class="item">
                    <img src="/resources/umi-rockband/images/slider-img.jpg" alt="" />
                    <div class="carousel-caption">
                        <h1>UMI ROCK THEME</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat </p>
                        <a href="#" class="btn btn-custom btn-warning">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Слайдер на главной <Конец> -->

    <!-- Статические страницы <Начало> -->
    <xsl:template match="contents[@controller = 'structure.index'][page]" mode="content">
            <xsl:call-template name="mainSlider" />
            <textarea id="debug">
                <xsl:copy-of select="."/>
            </textarea>
    </xsl:template>
    <!-- Статические страницы <Конец> -->

    <!-- Меню в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="footerMenu">
        <xsl:apply-templates select="menu/item" mode="footerMenu"/>
    </xsl:template>

    <xsl:template match="item" mode="footerMenu">
        <div class="col-md-2">
            <h5><xsl:value-of select="page/@displayName"/></h5>
            <ul class="menu">
                <xsl:apply-templates select="children/item" mode="footerMenuLevel2"/>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="footerMenuLevel2">
        <li><a href="page/@url">
            <xsl:value-of select="page/@displayName" />
        </a></li>
    </xsl:template>
    <!-- Меню в футере <Конец> -->

    <!-- Копирайт в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="copyright">
        <!--<xsl:value-of select="infoBlock/property[@name = 'copyright']/value" disable-output-escaping="yes"/>-->
        UMI.CMS 2014 Все права защищены
    </xsl:template>
    <!-- Копирайт в футере <Конец> -->

</xsl:stylesheet>