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

    <xsl:template match="/layout">
        <html>
            <head>

                <meta charset="utf-8"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <meta name="description" content="{description}" />
                <meta name="keywords" content="{keywords}" />
                <meta name="author" content=""/>

                <title><xsl:value-of select="title" /></title>
                <base href="{assetsUrl}" />
                <link rel="shortcut icon" href="/favicon.ico"/>


                <!-- Bootstrap core CSS -->
                <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>

                <!-- Custom styles for this template -->
                <link href="css/project.css" rel="stylesheet"/>

                <!-- Just for debugging purposes. Don't actually copy this line! -->
                <!--[if lt IE 9]><script src="/bootstrap/js/ie8-responsive-file-warning.js"></script><![endif]-->

                <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                <![endif]-->

            </head>
            <body>

                <div class="navbar navbar-default navbar-static-top" role="navigation">
                    <div class="container">

                        <div class="navbar-collapse collapse">

                            <xsl:apply-templates select="document('widget://structure.menu.auto?depth=1')/result" mode="auto"/>

                            <xsl:apply-templates select="locales" mode="layout"/>


                        </div>

                    </div>
                </div>

                <div class="container">

                    <header class="blog-header">
                        <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')/result" mode="logo"/>
                    </header>

                    <main class="row">

                        <section class="col-sm-8 blog-main">

                            <xsl:apply-templates select="contents" mode="layout"/>

                        </section>

                        <aside class="col-sm-3 col-sm-offset-1 blog-sidebar">
                            <div class="sidebar-module sidebar-module-inset">
                                <xsl:apply-templates select="document('widget://users.profile.view')/result" mode="sideBar"/>
                            </div>

                            <div class="sidebar-module sidebar-module-inset">
                                <h4><xsl:value-of select="document('translate://project.site/RSSFeed')/result"/></h4>
                                <p>
                                    <xsl:value-of select="document('translate://project.site/News')/result"/>:
                                    <xsl:apply-templates select="document('widget://news.item.rssLink')/result"/>
                                </p>
                            </div>
                            <div class="sidebar-module">
                                <h4><xsl:value-of select="document('translate://project.site/Rubrics')/result"/></h4>
                                <xsl:apply-templates select="document('widget://news.rubric.tree')/result" mode="short"/>
                            </div>
                            <div class="sidebar-module">
                                <h4><xsl:value-of select="document('translate://project.site/Subjects')/result"/></h4>
                                <xsl:apply-templates select="document('widget://news.subject.list')/result" mode="short"/>
                            </div>

                            <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')/result" mode="social"/>


                        </aside>

                    </main>

                </div>

                <footer class="blog-footer">

                    <xsl:apply-templates select="document('widget://structure.menu.custom?branch=104be372-0550-469d-9a63-f36a361139a5')/result" mode="bottomMenu"/>

                    <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')/result" mode="footer"/>
                    <p>
                        <a href="{$anchor}"><xsl:value-of select="document('translate://project.site/BackToTop')/result"/></a>
                    </p>
                </footer>


                <!-- Bootstrap core JavaScript
                ================================================== -->
                <!-- Placed at the end of the document so the pages load faster -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
                <script src="bootstrap/js/bootstrap.min.js"></script>
                <script src="bootstrap/js/docs.min.js"></script>
            </body>
        </html>

    </xsl:template>

</xsl:stylesheet>