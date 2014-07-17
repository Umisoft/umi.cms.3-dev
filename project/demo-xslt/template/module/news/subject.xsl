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

    <xsl:template match="result[@widget = 'news.subject.rssLink']">
        <a href="{url}"><xsl:value-of select="document('translate://project.site/RSSFeed')/result"/></a>
    </xsl:template>

    <xsl:template match="result[@widget = 'news.subject.list']" mode="short">
        <xsl:apply-templates select="list/collection[@name = 'newsSubject']" mode="subjectsList"/>
    </xsl:template>

    <xsl:template match="collection[item]" mode="subjectsList">
        <ul>
            <xsl:apply-templates select="item" mode="subjectsList"/>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="subjectsList">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName"/>
            </a>
            (<xsl:apply-templates select="document(concat('widget://news.subject.rssLink?subject=', @guid))/result"/>)
        </li>
    </xsl:template>

</xsl:stylesheet>