<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:template match="result[@widget = 'news.subject.list']/list/collection" mode="news.sidebar.rss">
        <ul>
            <xsl:apply-templates select="item" mode="news.sidebar.rss"/>
        </ul>
    </xsl:template>

    <xsl:template match="result[@widget = 'news.subject.list']/list/collection/item" mode="news.sidebar.rss">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            <xsl:apply-templates select="document(concat('widget://news.subject.rssLink?subject=', @guid))" mode="news.sidebar.rss"/>
        </li>
    </xsl:template>

    <xsl:template match="result[@widget = 'news.subject.rssLink']" mode="news.sidebar.rss">
        <a href="{url}" class="rss"></a>
    </xsl:template>

</xsl:stylesheet>