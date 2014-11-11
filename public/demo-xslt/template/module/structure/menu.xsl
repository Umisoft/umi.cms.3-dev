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

    <xsl:template match="result[@widget = 'structure.menu.auto'][menu/item]" mode="auto">
        <ul class="nav navbar-nav">
            <xsl:apply-templates select="menu/item" mode="auto"/>
        </ul>
    </xsl:template>

    <xsl:template match="menu/item" mode="auto">
        <li>
            <xsl:attribute name="class">
                <xsl:if test="active = 1">active</xsl:if>
            </xsl:attribute>
            <a href="{page/@url}"><xsl:value-of select="page/@displayName" /></a>
        </li>
    </xsl:template>

    <xsl:template match="result[@widget = 'structure.menu.custom'][tree/item]" mode="bottomMenu">
        <ul class="bottomMenu">
            <xsl:apply-templates select="tree/item" mode="bottomMenu"/>
        </ul>
    </xsl:template>

    <xsl:template match="tree/item" mode="bottomMenu">
        <li>
            <xsl:attribute name="class">
                <xsl:if test="active = 1">active</xsl:if>
            </xsl:attribute>
            <xsl:choose>
                <xsl:when test="current = 1">
                    <xsl:value-of select="@displayName" />
                </xsl:when>
                <xsl:otherwise>
                    <a href="{@url}">
                        <xsl:if test="@type='externalItem'">
                            <xsl:attribute name="target">blank</xsl:attribute>
                        </xsl:if>
                        <xsl:value-of select="@displayName" />
                    </a>
                </xsl:otherwise>
            </xsl:choose>
        </li>
    </xsl:template>

</xsl:stylesheet>