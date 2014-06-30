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

    <!-- Телефон в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="phone">
        <span class="phone">
            <xsl:value-of select="infoBlock/property[@name = 'phoneNumber']/value" disable-output-escaping="yes"/>
        </span>
    </xsl:template>
    <!-- Телефон в хедере <Конец> -->

    <!-- Копирайт в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="copyright">
        <!--<xsl:value-of select="infoBlock/property[@name = 'copyright']/value" disable-output-escaping="yes"/>-->
        UMI.CMS 2014 Все права защищены
    </xsl:template>
    <!-- Копирайт в футере <Конец> -->

    <!-- Шаблон для логотипа <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="logo">
        <xsl:value-of select="infoBlock/property[@name='logo']/value" disable-output-escaping="yes"/>
    </xsl:template>
    <!-- Шаблон для логотипа <Конец> -->

    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="footer">
        <xsl:value-of select="infoBlock/property[@name='share']/value" disable-output-escaping="yes"/>
        <xsl:value-of select="infoBlock/property[@name='copyright']/value" disable-output-escaping="yes"/>
        <xsl:value-of select="infoBlock/property[@name='address']/value" disable-output-escaping="yes"/>
        <xsl:value-of select="infoBlock/property[@name='phoneNumber']/value" disable-output-escaping="yes"/>
    </xsl:template>



</xsl:stylesheet>