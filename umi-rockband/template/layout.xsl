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

    <xsl:include href="template://module/structure/common" />
    <xsl:include href="template://module/news/common" />
    <xsl:include href="template://module/blog/common" />
    <xsl:include href="template://system/common" />

    <xsl:variable name="root" select="/layout/projectUrl" />

    <xsl:template match="/layout">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
                <meta name="Description" content="{description}" />
                <meta name="Keywords" content="{keywords}" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title><xsl:value-of select="title" /></title>
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/reset.css" />
                <link rel="stylesheet" href="/resources/umi-rockband/css/bootstrap.css" type="text/css" />
                <link rel="stylesheet" href="/resources/umi-rockband/css/bootstrap-theme.css" type="text/css" />
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/style.css" />
                <link type="text/css" rel="stylesheet" href="/resources/umi-rockband/css/bootstrap-class.css" />
                <link rel="shortcut icon" href="/resources/umi-rockband/images/favicon.ico" />
                <script src="/resources/umi-rockband/js/jquery-1.11.0.min.js"></script>
                <script type="text/javascript" src="/resources/umi-rockband/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="/resources/umi-rockband/js/scripts.js"></script>
            </head>
            <body>
                <!-- Хедер сайта -->
                <xsl:call-template name="header" />

                <div class="header-height"></div>

                <!-- Основное содержание -->
                <xsl:apply-templates select="contents" />

                <!-- Футер сайта -->
                <xsl:call-template name="footer" />
            </body>
        </html>

    </xsl:template>

</xsl:stylesheet>