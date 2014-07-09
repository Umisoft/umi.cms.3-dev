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

    <xsl:template match="result[@widget = 'news.rubric.rssLink']">
        <a href="{url}"><xsl:value-of select="document('translate://project.site/RSSFeed')/result"/></a>
    </xsl:template>

    <xsl:template match="result[@widget = 'news.rubric.tree']"  mode="short">
        <xsl:apply-templates select="tree" mode="rubricsTree"/>
    </xsl:template>

    <xsl:template match="tree[item]" mode="rubricsTree">
        <ul>
            <xsl:apply-templates select="item" mode="rubricsTree"/>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="rubricsTree">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            (<xsl:apply-templates select="document(concat('widget://news.rubric.rssLink?rubric=', @guid))/result"/>)
        </li>
    </xsl:template>

    <xsl:template match="item[item]" mode="rubricsTree">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
             (<xsl:apply-templates select="document(concat('widget://news.rubric.rssLink?rubric=', @guid))/result"/>)
            <ul>
                <xsl:apply-templates select="item" mode="rubricsTree"/>
            </ul>
        </li>
    </xsl:template>

</xsl:stylesheet>