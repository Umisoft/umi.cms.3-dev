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

    <!-- Языки в хедере <Начало> -->
    <xsl:template match="locales[locale]">
        <ul class="langs">
            <xsl:apply-templates select="locale" />
        </ul>
    </xsl:template>

    <xsl:template match="locale">
        <li><a href="{@url}">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[@current = '1']">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[position() = last()]">
        <li><a href="{@url}">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a></li>
    </xsl:template>

    <xsl:template match="locale[@current = '1' and position() = last()]">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a></li>
    </xsl:template>
    <!-- Языки в хедере <Конец> -->


</xsl:stylesheet>