<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Шаблоны для хлебных крошек <Начало> -->
    <xsl:template match="breadcrumbs">
        <xsl:apply-templates select="item" mode="breadcrumbs"/>
    </xsl:template>

    <xsl:template match="item" mode="breadcrumbs">
        <a href="{url}">
            <xsl:value-of select="displayName"/>
        </a>
        <xsl:text> / </xsl:text>
    </xsl:template>

    <xsl:template match="item[position() = last()]" mode="breadcrumbs">
        <a href="{url}">
            <xsl:value-of select="displayName"/>
        </a>
    </xsl:template>
    <!-- Шаблоны для для хлебных крошек <Конец> -->

</xsl:stylesheet>