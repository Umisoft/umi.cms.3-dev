<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Отдельный пост на главной -->
    <xsl:template match="blogPost" mode="mainPost">
        <div class="item col-md-4 blue col-sm-5 col-xs-12">
            <a href="{@url}"><img src="/resources/umi-rockband/images/list-blog/1.jpg" class="img" alt="" /></a>
            <div class="bottom">
                <h4><a href="{@url}" class="title">
                    <xsl:value-of select="property[@name='metaTitle' or @name='title']/value"/>
                </a></h4>
                <div class="desc">
                    <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
                </div>
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

    <!-- Отдельный пост в категории блога -->
    <xsl:template match="blogPost" mode="blogPost">
        <div class="article">
            <h3 class="article-title">
                <a href="{@url}">
                    <xsl:value-of select="@displayName" />
                </a>
            </h3>
            <div class="more">
                <div class="name">
                    <xsl:value-of select="property[@name = 'author']/value/@displayName" />
                </div>
                <div class="date">
                    <!-- 16 июня 2014, 15:05 -->
                    <xsl:value-of select="property[@name = 'publishTime']/value/date" />
                </div>
                <div class="view">
                    21 829
                </div>
                <div class="comments">
                    <xsl:apply-templates select="property[@name = 'commentsCount']/value" mode="number" />
                </div>

                <div class="settings">
                    <a href="{@url}"></a>
                </div>
            </div>
            <div class="article-content wide">
                <img src="/resources/umi-rockband/blog/images/article2-img.png" alt="{displayName}" />
                <div class="content">
                    <xsl:value-of select="property[@name = 'announcement']/value" disable-output-escaping="yes" />
                </div>
                <a class="moar" href="{@url}">
                    <xsl:value-of select="document('translate://project.site.blog/ReadMore')/result" />
                </a>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>