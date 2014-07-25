<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">


    <!-- Список постов <Начало> -->
    <xsl:template match="result[@widget = 'blog.post.view.list']"  mode="blog.content.list">
        <xsl:apply-templates select="list/collection/item" mode="blog.content.list" />
        <xsl:apply-templates select="paginator" />
    </xsl:template>

    <!-- Вызов кажого поста <Начало> -->
    <xsl:template match="item" mode="blog.content.list">
        <xsl:apply-templates
                select="document(concat('widget://blog.post.view.view?blogPost=', @guid))/result/blogPost"/>
    </xsl:template>
    <!-- Вызов каждого поста <Конец> -->

    <!-- Отдельный пост <Начало> -->
    <xsl:template match="result[@widget = 'blog.post.view.view']/blogPost">
        <div class="article">
            <h3 class="article-title">
                <a href="{@url}">
                    <xsl:value-of select="@displayName" disable-output-escaping="yes" />
                </a>
            </h3>
            <div class="more">
                <div class="name">
                    <xsl:value-of select="property[@name = 'author']/value/@displayName" />
                </div>
                <div class="date">
                    <xsl:call-template name="dateTime">
                        <xsl:with-param name="format">d F Y, G:i</xsl:with-param>
                        <xsl:with-param name="stringTime" select="property[@name = 'publishTime']/value/date"/>
                    </xsl:call-template>
                </div>
                <div class="comments">
                    <xsl:apply-templates select="property[@name = 'commentsCount']/value" mode="number" />
                </div>
            </div>
            <div class="article-content wide">
                <img src="{$template}/blog/images/article2-img.png" alt="{@displayName}" />
                <div class="content">
                    <xsl:value-of select="property[@name = 'announcement']/value" disable-output-escaping="yes" />
                </div>
                <a class="moar" href="{@url}">
                    <xsl:value-of select="document('translate://project.site.blog/ReadMore')/result" />
                </a>
            </div>
        </div>
    </xsl:template>
    <!-- Отдельный пост <Конец> -->

    <!-- Список постов <Конец> -->


    <!-- Список последних постов блога на главной <Начало> -->
    <xsl:template match="result[@widget = 'blog.post.view.list']" mode="blog.main.row" >
        <div class="list-blog row">
            <xsl:apply-templates select="list/collection/item" mode="blog.main.row"/>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="blog.main.row">
        <xsl:apply-templates select="document(concat('widget://blog.post.view.view?blogPost=', @guid))/result/blogPost"
                             mode="blog.main.row"/>
    </xsl:template>

    <!-- Отдельный пост на главной -->
    <xsl:template match="blogPost" mode="blog.main.row">
        <div class="item col-md-4 blue col-sm-5 col-xs-12">
            <a href="{@url}"><img src="{$template}/images/list-blog/1.jpg" class="img" alt="" /></a>
            <div class="bottom">
                <h4>
                    <a href="{@url}" class="title"><xsl:value-of select="property[@name='h1']/value"/></a>
                </h4>
                <div class="desc">
                    <xsl:value-of select="property[@name='announcement']/value" disable-output-escaping="yes"/>
                </div>
                <ul class="info">
                    <li class="date">
                        <xsl:call-template name="dateTime">
                            <xsl:with-param name="format">d F Y</xsl:with-param>
                            <xsl:with-param name="stringTime" select="property[@name='publishTime']/value/date"/>
                        </xsl:call-template>
                    </li>
                    <li class="cat">
                        <xsl:value-of select="property[@name='category']/value/@displayName" />
                        <xsl:text> | </xsl:text>
                        <xsl:apply-templates select="property[@name='commentsCount']/value" mode="number"/>
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="document('translate://project.site/Comments')" />
                    </li>
                </ul>
            </div>
        </div>
    </xsl:template>
    <!-- Список последних постов блога на главной <Конец> -->

</xsl:stylesheet>