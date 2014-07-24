<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Телефон в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="phone">
        <span class="phone">
            <xsl:value-of select="infoBlock/property[@name = 'phoneNumber']/value" disable-output-escaping="yes"/>
        </span>
    </xsl:template>
    <!-- Телефон в хедере <Конец> -->

    <!-- Копирайт в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="copyright">
        <xsl:value-of select="infoBlock/property[@name = 'copyright']/value" disable-output-escaping="yes"/>
    </xsl:template>
    <!-- Копирайт в футере <Конец> -->

    <!-- Шаблон для логотипа <Начало> -->
    <xsl:template match="result[@widget = 'structure.infoblock.view']" mode="logo">
        <a href="{$root}" class="logo">
            <xsl:value-of select="infoBlock/property[@name = 'logo']/value" disable-output-escaping="yes"/>
        </a>
    </xsl:template>
    <!-- Шаблон для логотипа <Конец> -->

    <!-- Значки социальных сетей <Начало> -->
    <xsl:template name="footerSocial">
        <ul class="social">
            <li>
                <a href="#">
                    <img src="{$template}/images/social/facebook.png" alt=""/>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{$template}/images/social/twitter.png" alt=""/>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{$template}/images/social/google.png" alt=""/>
                </a>
            </li>
        </ul>
    </xsl:template>
    <!-- Значки социальных сетей <Конец> -->

</xsl:stylesheet>