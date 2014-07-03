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
    <xsl:template match="result[@widget = 'blog.post.view.list']" mode="mainList">
        <div class="row list-blog">
            <xsl:apply-templates select="list/collection/item" mode="mainList"/>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="mainList">
        <xsl:apply-templates select="document(concat('widget://blog.post.view.view?blogPost=', @guid))/result/blogPost"
                             mode="mainPost"/>
    </xsl:template>
    <!-- Список последних постов блога на главной <Конец> -->

    <!-- Список последних постов блога в категории <Начало> -->
    <xsl:template match="result[@widget = 'blog.category.postList']" mode="blogList">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <xsl:apply-templates select="list/collection/item" mode="blogList"/>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="blogList">
        <xsl:apply-templates select="document(concat('widget://blog.post.view.view?blogPost=', @guid))/result/blogPost"
                             mode="blogPost"/>
    </xsl:template>
    <!-- Список последних постов блога в категории <Конец> -->

</xsl:stylesheet>