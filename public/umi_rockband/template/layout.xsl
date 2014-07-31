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

    <xsl:include href="template://system/common"/>

    <xsl:template match="/layout">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html, charset=utf-8"/>
                <meta name="Description" content="{description}"/>
                <meta name="Keywords" content="{keywords}"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title>
                    <xsl:value-of select="title"/>
                </title>
                <link rel="stylesheet" href="{$template}/css/reset.css"/>
                <link rel="stylesheet" href="{$template}/css/bootstrap.css"/>
                <link rel="stylesheet" href="{$template}/css/bootstrap-theme.css"/>
                <link rel="stylesheet" href="{$template}/css/style.css"/>
                <link rel="stylesheet" href="{$template}/css/bootstrap-class.css"/>
                <link rel="shortcut icon" href="{$template}/images/favicon.ico"/>
                <script src="{$template}/js/jquery-1.11.0.min.js"></script>
                <script src="{$template}/js/bootstrap.min.js"></script>
                <script src="{$template}/js/scripts.js"></script>
            </head>
            <body>
                <!-- Хедер сайта -->
                <xsl:call-template name="header"/>

                <div class="header-height"></div>

                <!-- Основное содержание -->
                <xsl:apply-templates select="contents"/>

                <!-- Футер сайта -->
                <xsl:call-template name="footer"/>

                <!-- Админ тулбар -->
                <xsl:apply-templates select="document('widget://topBarw')" />
            </body>
        </html>

    </xsl:template>

</xsl:stylesheet>