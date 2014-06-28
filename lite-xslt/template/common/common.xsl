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


    <xsl:template match="page" mode="layout">
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

</xsl:stylesheet>