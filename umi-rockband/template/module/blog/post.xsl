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

    <!-- Список последних постов блога на главной <Начало> -->
    <xsl:template match="result[@widget = 'blog.post.view.list']" mode="mainPage">
        <div class="row list-blog">
            <xsl:apply-templates select="list/collection/item" mode="mainPage"/>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="mainPage">
        <xsl:apply-templates select="document(concat('widget://blog.post.view.list?newsItem=', @guid))/result/newsItem"
                             mode="mainPage"/>
    </xsl:template>

    <!-- Отдельная новость -->
    <xsl:template match="newsItem" mode="mainPage">
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
    <!-- Список последних постов блога на главной <Конец> -->



</xsl:stylesheet>