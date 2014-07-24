<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей на странице Сюжета <Начало> -->
    <xsl:template match="result[@widget = 'news.subject.newsList']" mode="news.content.list">
        <xsl:apply-templates select="list/collection" mode="news.content.list" />
        <xsl:apply-templates select="paginator"/>
    </xsl:template>

    <!-- Если нет новостей омеченных этим Сюжетом -->
    <xsl:template match="result[@widget = 'news.subject.newsList']/list/collection" mode="news.content.list">
        <xsl:value-of select="document('translate://project.site.search/No%20result')/result" />
    </xsl:template>

    <!-- Если есть новости омеченные этим Сюжетом -->
    <xsl:template match="result[@widget = 'news.subject.newsList']/list/collection[item]" mode="news.content.list">
        <xsl:apply-templates select="item" mode="news.content.list"/>
    </xsl:template>
    <!-- Список новостей на странице Сюжета <Конец> -->

    <!-- Список сюжетов для вывода на странице <Начало> -->
    <xsl:template match="result[@widget = 'news.subject.list']" mode="news.content.list">
        <xsl:apply-templates select="list/collection/item" mode="news.content.subject" />
    </xsl:template>

    <xsl:template match="item" mode="news.content.subject">
        <h3><a href="{@url}"><xsl:value-of select="@displayName" /></a></h3>
        <xsl:value-of select="//property[@name='contents']/value" disable-output-escaping="yes" />
    </xsl:template>
    <!-- Список сюжетов для вывода на странице <Конец> -->

    <!-- Список сюжетов для сайдбара <Начало> -->
    <xsl:template match="result[@widget = 'news.subject.list']/list/collection" mode="news.sidebar.list">
        <ul>
            <xsl:apply-templates select="item" mode="news.sidebar.list"/>
        </ul>
    </xsl:template>

    <!-- Вывод каждого сюжета <Начало> -->
    <xsl:template match="result[@widget = 'news.subject.list']/list/collection/item" mode="news.sidebar.list">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            <!-- Кнопка Rss -->
            <xsl:apply-templates select="document(concat('widget://news.subject.rssLink?subject=', @guid))" mode="news.sidebar.rss"/>
        </li>
    </xsl:template>
    <!-- Вывод каждого сюжета <Конец> -->

    <!-- Кнопка Rss -->
    <xsl:template match="result[@widget = 'news.subject.rssLink']" mode="news.sidebar.rss">
        <a href="{url}" class="rss"></a>
    </xsl:template>
    <!-- Список сюжетов для сайдбара <Конец> -->

</xsl:stylesheet>