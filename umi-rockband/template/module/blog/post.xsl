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
                             mode="mainList"/>
    </xsl:template>

    <!-- Отдельный пост -->
    <xsl:template match="blogPost" mode="mainList">
        <textarea>
            <xsl:copy-of select="."/>
        </textarea>
        <div class="item col-md-4 blue col-sm-5 col-xs-12">
            <a href="{@url}"><img src="/resources/umi-rockband/images/list-blog/1.jpg" class="img" alt="" /></a>
            <div class="bottom">
                <h4><a href="{@url}" class="title">
                    <xsl:value-of select="property[@name='metaTitle' or @name='title']/value"/>
                </a></h4>
                <p class="desc">
                    <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
                </p>
                <ul class="info">
                    <li class="date">
                        <!-- DateTime format: May 6, 2014 at 7:53am -->
                        <xsl:value-of select="property[@name='publishTime']/value/date" />
                    </li>
                    <li class="cat">
                        <xsl:value-of select="property[@name='category']/value/@displayName" />
                        <xsl:text> | </xsl:text>
                        <xsl:apply-templates select="property[@name='commentsCount']/value" mode="number"/>
                        <xsl:text> комментариев</xsl:text>

                        <!--Музыка  |  22 -->
                    </li>
                </ul>
            </div>
        </div>
    </xsl:template>
    <!-- Список последних постов блога на главной <Конец> -->


</xsl:stylesheet>