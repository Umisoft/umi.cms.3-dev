<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

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
        <li class="col-md-3 col-sm-6" >
            <a href="{@url}"><img src="/resources/umi-rockband/images/list-dev/1.jpg" class="img" alt="" /></a><br />
            <a href="{@url}" class="title">
                <xsl:value-of select="property[@name='metaTitle' or @name='title']/value"/>
            </a>
            <span class="date">
                <!-- DateTime format: May 6, 2014 at 7:53am -->
                <xsl:value-of select="property[@name='created']/value/date" />
            </span>
            <p class="desc">
                <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
            </p>
        </li>
    </xsl:template>
    <!-- Список свежих новостей на главной <Конец> -->

</xsl:stylesheet>