<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей <Начало> -->
    <xsl:template match="result[@widget = 'news.item.list']">
        <xsl:apply-templates select="list/collection/item"/>
    </xsl:template>

    <xsl:template match="result[@widget = 'news.item.list']/list/collection/item">
        <xsl:apply-templates
                select="document(concat('widget://news.item.view?newsItem=', @guid))/result/newsItem" />
    </xsl:template>

    <!-- Отдельная новость <Начало> -->
    <xsl:template match="result[@widget = 'news.item.view']/newsItem">
        <div class="article">
            <h3 class="article-title">
                <xsl:value-of select="@displayName"/>
            </h3>
            <div class="more">
                <div class="name">
                    <xsl:value-of select="property[@name='owner']/value/@displayName"/>
                </div>
                <div class="date">
                    <xsl:call-template name="dateTime">
                        <xsl:with-param name="format">d F Y, G:i</xsl:with-param>
                        <xsl:with-param name="stringTime" select="property[@name='created']/value/date"/>
                    </xsl:call-template>
                </div>
                <!--div class="view">
                    21 829
                </div>
                <div class="comments">
                    213
                </div-->
                <div class="settings">
                    <div class="dropdown">
                        <a class="account setting"></a>

                        <div class="submenu">
                            <ul class="root">
                                <li>
                                    <a href="#" class="q1">
                                        <i class="glyphicon glyphicon-ok"></i>
                                        Опубликовать
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="q2">
                                        <i class="glyphicon glyphicon-remove"></i>
                                        Отклонить
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="q3">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                        В черновики
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="article-content">
                <a href="#">
                    <img src="images/article-img.png" alt=""/>
                </a>
                <div class="content">
                    <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
                </div>
                <a href="{@url}" class="btn btn-custom btn-default-font btn-noshadow btn-primary">Читать</a>
            </div>
        </div>
    </xsl:template>
    <!-- Отдельная новость <Конец> -->

    <!-- Список новостей <Конец> -->


    <!-- Список свежих новостей на главной <Начало> -->
    <xsl:template match="result[@widget = 'news.item.list']" mode="mainNewsList">
        <ul class="list-dev row">
            <xsl:apply-templates select="list/collection/item" mode="mainPage"/>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="mainPage">
        <xsl:apply-templates select="document(concat('widget://news.item.view?newsItem=', @guid))/result/newsItem"
                             mode="mainNewsList"/>
    </xsl:template>

    <!-- Отдельная новость -->
    <xsl:template match="newsItem" mode="mainNewsList">
        <li class="col-md-3 col-sm-6">
            <a href="{@url}">
                <img src="/resources/umi-rockband/images/list-dev/1.jpg" class="img" alt=""/>
            </a>
            <br/>
            <a href="{@url}" class="title">
                <xsl:value-of select="property[@name='metaTitle' or @name='title']/value"/>
            </a>
            <span class="date">
                <!-- DateTime format: May 6, 2014 at 7:53am -->
                <xsl:value-of select="property[@name='created']/value/date"/>
            </span>
            <p class="desc">
                <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
            </p>
        </li>
    </xsl:template>
    <!-- Список свежих новостей на главной <Конец> -->

</xsl:stylesheet>