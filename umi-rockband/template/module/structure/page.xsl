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

    <!-- Статические страницы <Начало> -->
    <xsl:template match="contents[@controller = 'structure.index'][page]" mode="content">
        <xsl:call-template name="mainSlider" />

        <div class="content-main">
            <div class="container-fluid">
                <!-- Последние новости  -->
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
            <!-- О нас -->
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
            <!-- Контент -->
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
            <!-- Последние посты блогов -->
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
    </xsl:template>
    <!-- Статические страницы <Конец> -->

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


</xsl:stylesheet>