<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей из Рубрики <Начало> -->
    <xsl:template match="result[@widget = 'news.rubric.newsList']" mode="news.content.list">
        <xsl:apply-templates select="list/collection" mode="news.content.list" />
        <xsl:apply-templates select="paginator"/>
    </xsl:template>

    <!-- Если в Рубрике нет новостей -->
    <xsl:template match="result[@widget = 'news.rubric.newsList']/list/collection" mode="news.content.list">
        Пустота
    </xsl:template>

    <!-- Если в Рубрике есть новости -->
    <xsl:template match="result[@widget = 'news.rubric.newsList']/list/collection[item]" mode="news.content.list">
        <xsl:apply-templates select="item" mode="news.content.list"/>
    </xsl:template>
    <!-- Список новостей из Рубрики <Конец> -->

    <!-- Список рубрик для вывода на странице <Начало> -->
    <xsl:template match="result[@widget = 'news.rubric.list']" mode="news.content.list">
        <xsl:apply-templates select="list/collection/item" mode="news.content.rubrics" />
    </xsl:template>

    <xsl:template match="item" mode="news.content.rubrics">
        <h3><a href="{@url}"><xsl:value-of select="@displayName" /></a></h3>
        <xsl:value-of select="//property[@name='contents']/value" disable-output-escaping="yes" />
    </xsl:template>
    <!-- Список рубрик для вывода на странице <Конец> -->

    <!-- Список рубрик для вывода в Сайдбаре <Начало> -->
    <xsl:template match="result[@widget = 'news.rubric.list']/list/collection" mode="news.sidebar.rss">
        <ul>
            <xsl:apply-templates select="item" mode="news.sidebar.rss" />
        </ul>
    </xsl:template>

    <!-- Вывод каждой рубрики и рекурсивный вызов для вывода дочерних рубрик <Начало> -->
    <xsl:template match="result[@widget = 'news.rubric.list']/list/collection/item" mode="news.sidebar.rss">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            <!-- Кнопка RSS -->
            <xsl:apply-templates select="document(concat('widget://news.rubric.rssLink?rubric=', @guid))" mode="news.sidebar.rss" />
            <!-- Рекурсивный вызов -->
            <xsl:apply-templates select="document(concat('widget://news.rubric.list?parentRubric=', @guid))" mode="news.sidebar.rss" />
        </li>
    </xsl:template>
    <!-- Вывод каждой рубрики и рекурсивный вызов для вывода дочерних рубрик <Конец> -->

    <!-- Кнопка RSS -->
    <xsl:template match="result[@widget = 'news.rubric.rssLink']" mode="news.sidebar.rss">
        <a href="{url}" class="rss"></a>
    </xsl:template>
    <!-- Список рубрик для вывода в Сайдбаре <Конец> -->

</xsl:stylesheet>