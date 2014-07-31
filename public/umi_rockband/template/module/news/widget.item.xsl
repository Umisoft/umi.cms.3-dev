<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей <Начало> -->
    <xsl:template match="result[@widget = 'news.item.list']">
        <xsl:apply-templates select="list/collection/item" mode="news.content.list" />
        <xsl:apply-templates select="paginator" />
    </xsl:template>

    <!-- Вызов каждой новости <Начало> -->
    <xsl:template match="item" mode="news.content.list">
        <xsl:apply-templates
                select="document(concat('widget://news.item.view?newsItem=', @guid))/result/newsItem"/>
    </xsl:template>
    <!-- Вызов каждой новости <Конец> -->

    <!-- Отдельная новость <Начало> -->
    <xsl:template match="result[@widget = 'news.item.view']/newsItem">
        <div class="article">
            <h3 class="article-title">
                <xsl:value-of select="@displayName" disable-output-escaping="yes" />
            </h3>
            <div class="more">
                <div class="date">
                    <xsl:call-template name="dateTime">
                        <xsl:with-param name="format">d F Y, G:i</xsl:with-param>
                        <xsl:with-param name="stringTime" select="property[@name='created']/value/date"/>
                    </xsl:call-template>
                </div>
            </div>
            <div class="article-content">
                <a href="{@url}">
                    <img src="{property[@name='image']/value}" alt="{@displayName}"/>
                </a>
                <div class="content">
                    <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
                </div>
                <a href="{@url}" class="btn btn-custom btn-default-font btn-noshadow btn-primary">
                    <xsl:value-of select="document('translate://project.site.news/ReadMore')/result"/>
                </a>
            </div>
        </div>
    </xsl:template>
    <!-- Отдельная новость <Конец> -->

    <!-- Список новостей <Конец> -->


    <!-- Список свежих новостей на главной <Начало> -->
    <xsl:template match="result[@widget = 'news.item.list']" mode="news.main.row" >
        <ul class="list-dev row">
            <xsl:apply-templates select="list/collection/item" mode="news.main.row"/>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="news.main.row">
        <xsl:apply-templates select="document(concat('widget://news.item.view?newsItem=', @guid))/result/newsItem"
                             mode="news.main.row"/>
    </xsl:template>

    <!-- Отдельная новость -->
    <xsl:template match="newsItem" mode="news.main.row">
        <li class="col-md-3 col-sm-6">
            <a href="{@url}">
                <img width="221" height="170" src="{property[@name='image']/value}" class="img" alt="{@displayName}"/>
                <!--<img src="{$template}/images/list-dev/1.jpg"  alt=""/>-->
            </a>
            <a href="{@url}" class="title">
                <xsl:value-of select="@displayName" disable-output-escaping="yes" />
            </a>
            <span class="date">
                <xsl:call-template name="dateTime">
                    <xsl:with-param name="format">Y-m-d H:i:s</xsl:with-param>
                    <xsl:with-param name="stringTime" select="property[@name='created']/value/date"/>
                </xsl:call-template>
            </span>
            <p class="desc">
                <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
            </p>
        </li>
    </xsl:template>
    <!-- Список свежих новостей на главной <Конец> -->

</xsl:stylesheet>