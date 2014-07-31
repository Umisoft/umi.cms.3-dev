<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список постов автора <Начало> -->
    <xsl:template match="result[@widget = 'blog.author.view.posts']"  mode="blog.content.list">
        <xsl:apply-templates select="list/collection/item" mode="blog.content.list" />
        <xsl:apply-templates select="paginator" />
    </xsl:template>
    <!-- Список постов автора <Конец> -->

    <!-- Список авторов для сайдбара <Начало> -->
    <xsl:template match="result[@widget = 'blog.author.view.list']/list/collection" mode="blog.sidebar.list">
        <ul>
            <xsl:apply-templates select="item" mode="blog.sidebar.author"/>
        </ul>
    </xsl:template>

    <!-- Вывод каждго автора <Начало> -->
    <xsl:template match="result[@widget = 'blog.author.view.list']/list/collection/item" mode="blog.sidebar.author">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            <!-- Кнопка Rss -->
            <xsl:apply-templates select="document(concat('widget://blog.author.rssLink?blogAuthor=', @guid))" mode="blog.sidebar.rss"/>
        </li>
    </xsl:template>
    <!-- Вывод каждого автора <Конец> -->

    <!-- Кнопка Rss -->
    <xsl:template match="result[@widget = 'blog.author.rssLink']" mode="blog.sidebar.rss">
        <a href="{url}" class="rss"></a>
    </xsl:template>
    <!-- Список авторов для сайдбара <Конец> -->

</xsl:stylesheet>