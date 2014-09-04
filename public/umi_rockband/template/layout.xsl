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
                <base href="{$assets}" />

                <link rel="stylesheet" href="css/bootstrap.min.css"/>
                <link rel="stylesheet" href="css/bootstrap-theme.min.css"/>
                <link rel="stylesheet" href="css/template.css"/>

                <link rel="shortcut icon" href="/favicon.ico"/>
                <script src="js/jquery-1.11.0.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="js/scripts.js"></script>
            </head>
            <body>
                <!-- Хедер сайта -->
                <xsl:call-template name="header"/>

                <!-- Основное содержание -->
                <xsl:apply-templates select="contents"/>

                <!-- Футер сайта -->
                <xsl:call-template name="footer"/>

                <!-- Админ тулбар -->
                <xsl:apply-templates select="document('widget://topBar')" />

                <!-- Pop-up окнка -->
                <div role="dialog" tabindex="-1" class="modal fade" id="auth" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title"><xsl:value-of select="document('translate://project.site.users/Login')/result"/></h4>
                            </div>
                            <div class="modal-body">
                                <xsl:apply-templates select="document('widget://users.authorization.loginForm')/result/form" mode="default.form">
                                    <xsl:with-param name="css.class">col-sm-12 col-md-12</xsl:with-param>
                                </xsl:apply-templates>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="dialog" tabindex="-1" class="modal fade" id="reg" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title"><xsl:value-of select="document('translate://project.site.users/Registration')/result"/></h4>
                            </div>
                            <div class="modal-body">
                                <xsl:apply-templates select="document('widget://users.registration.form')/result/form" mode="default.form">
                                    <xsl:with-param name="css.class">col-sm-12 col-md-12</xsl:with-param>
                                </xsl:apply-templates>
                            </div>
                        </div>
                    </div>
                </div>

            </body>
        </html>

    </xsl:template>

</xsl:stylesheet>
