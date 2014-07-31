<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Шаблон для форм <Начало> -->
    <xsl:template match="form">
        <xsl:param name="class" />
        <xsl:element name="{tag}">
            <xsl:if test="$class">
                <xsl:attribute name="class">
                    <xsl:value-of select="$class" />
                </xsl:attribute>
            </xsl:if>
            <xsl:apply-templates select="attributes/@*" mode="form"/>
            <xsl:apply-templates select="elements/item" mode="form"/>
        </xsl:element>
    </xsl:template>

    <xsl:template match="@*" mode="form">
        <xsl:attribute name="{name(.)}">
            <xsl:value-of select="."/>
        </xsl:attribute>
    </xsl:template>

    <xsl:template match="item" mode="form">
        <xsl:param name="class" />
        <xsl:element name="{tag}">
            <xsl:apply-templates select="attributes/@*" mode="form"/>
            <xsl:if test="$class">
                <xsl:attribute name="class">
                    <xsl:value-of select="$class" />
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="tag = 'button'">
                <xsl:attribute name="name" />
                <xsl:value-of select="label"/>
            </xsl:if>
        </xsl:element>
    </xsl:template>
    <!-- Шаблон для форм <Конец> -->

</xsl:stylesheet>