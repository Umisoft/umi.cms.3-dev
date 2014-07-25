<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список последних постов в Категории <Начало> -->
    <xsl:template match="result[@widget = 'blog.category.postList']" mode="blog.content.list">
        <xsl:apply-templates select="list/collection" mode="blog.content.list" />
        <xsl:apply-templates select="paginator"/>
    </xsl:template>

    <!-- Если в Категории нет постов -->
    <xsl:template match="result[@widget = 'blog.category.postList']/list/collection" mode="blog.content.list">
        <xsl:value-of select="document('translate://project.site.search/No%20result')/result" />
    </xsl:template>

    <!-- Если в Категории есть посты -->
    <xsl:template match="result[@widget = 'blog.category.postList']/list/collection" mode="blog.content.list">
        <xsl:apply-templates select="item" mode="blog.content.list"/>
    </xsl:template>
    <!-- Список последних постов в Категории <Конец> -->

    <!-- Список категорий для сайдбара <Начало> -->
    <xsl:template match="result[@widget = 'blog.category.list']/list/collection" mode="blog.sidebar.list">
        <ul>
            <xsl:apply-templates select="item" mode="blog.sidebar.list"/>
        </ul>
    </xsl:template>

    <!-- Вывод каждой категории и рекурсивный вызов для вывода дочерних категорий <Начало> -->
    <xsl:template match="result[@widget = 'blog.category.list']/list/collection/item" mode="blog.sidebar.list">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            <!-- Кнопка Rss -->
            <xsl:apply-templates select="document(concat('widget://blog.category.rssLink?category=', @guid))" mode="blog.sidebar.rss"/>
            <!-- Рекурсивный вызов -->
            <xsl:apply-templates select="document(concat('widget://blog.category.list?parentCategory=', @guid))" mode="blog.sidebar.rss" />
        </li>
    </xsl:template>
    <!-- Вывод каждой категории и рекурсивный вызов для вывода дочерних категорий <Конец> -->

    <!-- Кнопка Rss -->
    <xsl:template match="result[@widget = 'blog.category.rssLink']" mode="blog.sidebar.rss">
        <a href="{url}" class="rss"></a>
    </xsl:template>
    <!-- Список сюжетов для сайдбара <Конец> -->

</xsl:stylesheet>