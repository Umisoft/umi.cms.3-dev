<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список постов автора <Начало> -->
    <xsl:template match="result[@widget = 'blog.tag.postList']"  mode="blog.content.list">
        <xsl:apply-templates select="list/collection/item" mode="blog.content.list" />
        <xsl:apply-templates select="paginator" />
    </xsl:template>
    <!-- Список постов автора <Конец> -->

    <!-- Список тегов для Сайдбара <Начало> -->
    <xsl:template match="result[@widget = 'blog.tag.list']" mode="blog.sidebar.list">
        <ul class="tags">
            <xsl:apply-templates select="list/collection" mode="blog.sidebar.list" />
        </ul>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.tag.list']/list/collection/item" mode="blog.sidebar.list">
        <li>
            <a href="{@url}"><xsl:value-of select="@header" /></a>
        </li>
    </xsl:template>
    <!-- Список тегов для Сайдбара <Конец> -->

</xsl:stylesheet>