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


    <xsl:template match="contents" mode="layout">

        <xsl:apply-templates select="breadcrumbs" mode="layout"/>

        <xsl:apply-templates select="page" mode="layout"/>
    </xsl:template>

    <xsl:template match="page" mode="layout">
        <h1><xsl:value-of select="@header" /></h1>
        <xsl:value-of select="property[@name = 'contents']/value" disable-output-escaping="yes" />
    </xsl:template>

    <xsl:template match="locales[locale]" mode="layout">
        <ul class="nav navbar-nav navbar-right">
            <xsl:apply-templates select="locale" mode="layout"/>
        </ul>
    </xsl:template>

    <xsl:template match="locale" mode="layout">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <xsl:attribute name="class">
                    <xsl:if test="@current = 1">active</xsl:if>
                </xsl:attribute>
                <a href="{@url}"><xsl:value-of select="document(concat('translate://project.site/', @id))/result"/></a>
            </li>
        </ul>
    </xsl:template>

    <xsl:template match="breadcrumbs" mode="layout"/>

    <xsl:template match="breadcrumbs[count(item) > 1]" mode="layout">
        <ol class="breadcrumb">
            <xsl:apply-templates select="item" mode="breadcrumb"/>
        </ol>
    </xsl:template>

    <xsl:template match="item" mode="breadcrumb">
        <li>
            <xsl:attribute name="class">
                <xsl:if test="position() = last()">
                    active
                </xsl:if>
            </xsl:attribute>
            <a href="{url}"><xsl:value-of select="displayName"/></a>
        </li>
    </xsl:template>

</xsl:stylesheet>