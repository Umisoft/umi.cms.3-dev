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

    <xsl:template match="/">
        <html>
            <head>
                <meta charset="utf-8"/>

                <title><xsl:value-of select="result/title" /></title>
                <meta name="description" content="{result/description}" />
                <meta name="keywords" content="{result/keywords}" />

            </head>
            <body>


                <!-- xsl:apply-templates select="document('widget://structure.menu.auto')/result" /-->

                <xsl:apply-templates select="page/contents/page" />
            </body>
        </html>

    </xsl:template>

    <xsl:template match="page">
        <xsl:value-of select="property[@name = 'contents']/value" disable-output-escaping="yes" />
    </xsl:template>

    <!-- xsl:template match="result/contents[@widget = 'structure.menu.auto']">
        <ul>
            <xsl:apply-templates select="menu/item" />
        </ul>
    </xsl:template>

    <xsl:template match="menu/item">
        <li>
            <a href="#"><xsl:value-of select="page/@displayName" /></a>
        </li>
    </xsl:template -->

</xsl:stylesheet>