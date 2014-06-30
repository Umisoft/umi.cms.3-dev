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
    <xsl:include href="template://system/common" />


    <xsl:template match="/layout">
        <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
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
                <header id="top">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 col-md-2">
                                <ul class="langs">
                                    <xsl:apply-templates select="locales" />
                                </ul>
                                <br />
                                <a href="" class="logo"><img src="/resources/umi-rockband/images/logo.png" /></a>
                            </div>
                            <div class="right col-xs-12 col-sm-9 col-md-10 text-right">
                                <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="phone"/>
                                <br />
                                <xsl:apply-templates select="document('widget://structure.menu.auto?depth=1')" mode="headerMenu"/>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="header-height"></div>

                <xsl:apply-templates select="contents" mode="content"/>

                <footer>
                    <div class="container-fluid">
                        <div class="row">
                            <xsl:apply-templates select="document('widget://structure.menu.auto?depth=2')" mode="footerMenu"/>

                            <div class="col-md-12 text-center">
                                <ul class="social">
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/facebook.png" alt="" /></a></li>
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/twitter.png" alt="" /></a></li>
                                    <li><a href="#"><img src="/resources/umi-rockband/images/social/google.png" alt="" /></a></li>
                                </ul>
                                <p class="copy">
                                    <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="copyright"/>
                                </p>
                                <a href="#top" class="top-scroll"><img src="/resources/umi-rockband/images/top-scroll.png" alt=""/></a>
                            </div>
                        </div>
                    </div>
                </footer>
            </body>
        </html>

    </xsl:template>




</xsl:stylesheet>