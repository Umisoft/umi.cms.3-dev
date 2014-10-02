<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Статические страницы <Начало> -->
    <xsl:template match="contents[@controller = 'structure.index'][page/@isDefault = '1']">
        <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=slider')"/>

        <div class="content-main">
            <div class="container-fluid">
                <!-- Последние новости  -->
                <div class="block">
                    <div class="page-title">
                        <div class="title"><xsl:value-of select="document('translate://project.site.news/RecentEvents')/result"/></div>
                        <div class="separating-line"></div>
                        <div class="caption"><xsl:value-of select="document('translate://project.site.news/LatestNews')/result"/></div>
                    </div>
                    <!-- Список последних новостей  -->
                    <xsl:apply-templates select="document('widget://news.item.list?limit=4')" mode="news.main.row"/>
                </div>
            </div>
            <!-- О нас -->
            <div class="block">
                <div class="container-fluid text-center">
                    <div class="page-title">
                        <div class="title"><xsl:value-of select="document('translate://project.site.news/AboutUs')/result"/></div>
                        <div class="separating-line"></div>
                        <div class="caption"><xsl:value-of select="document('translate://project.site.blog/Most%20famous')"/></div>
                    </div>
                </div>
                <ul class="list-about">
                    <li>
                        <img src="images/about/1.jpg" alt=""/>
                    </li>
                    <li>
                        <img src="images/about/2.jpg" alt=""/>
                    </li>
                    <li>
                        <img src="images/about/3.jpg" alt=""/>
                    </li>
                    <li>
                        <img src="images/about/4.jpg" alt=""/>
                    </li>
                    <li>
                        <img src="images/about/5.jpg" alt=""/>
                    </li>
                </ul>
            </div>

            <!-- Контент -->
            <div class="container-fluid content-page">
                <xsl:value-of select="page/property[@name='contents']/value" disable-output-escaping="yes"/>
            </div>

            <!-- Последние посты блогов -->
            <div class="page-title">
                <div class="title"><xsl:value-of select="document('translate://project.site.news/Blog')/result"/></div>
                <div class="separating-line"></div>
                <div class="caption"><xsl:value-of select="document('translate://project.site.blog/RecentBlogPosts')/result"/></div>
            </div>

            <div class="main-blog">
                <div class="container-fluid">
                    <xsl:apply-templates select="document('widget://blog.post.view.list')" mode="blog.main.row"/>
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Статические страницы <Конец> -->

    <!-- Слайдер на главной <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view'][infoBlock/@type='slider']">
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
                    <img src="images/slider-img.jpg" alt=""/>
                    <div class="carousel-caption">
                        <h1>
                            <xsl:value-of select="//property[@name='slideName1']/value"/>
                        </h1>
                        <xsl:value-of select="//property[@name='slideContent1']/value" disable-output-escaping="yes" />
                    </div>
                </div>
                <div class="item">
                    <img src="images/slider-img-2.jpg" alt=""/>
                    <div class="carousel-caption">
                        <h1><xsl:value-of select="//property[@name='slideName2']/value"/></h1>
                        <xsl:value-of select="//property[@name='slideContent2']/value" disable-output-escaping="yes" />
                    </div>
                </div>
                <div class="item">
                    <img src="images/slider-img-3.jpg" alt=""/>
                    <div class="carousel-caption">
                        <h1><xsl:value-of select="//property[@name='slideName3']/value"/></h1>
                        <xsl:value-of select="//property[@name='slideContent3']/value" disable-output-escaping="yes" />
                    </div>
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Слайдер на главной <Конец> -->


</xsl:stylesheet>