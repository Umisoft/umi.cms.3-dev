<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:template name="header">
        <div class="header-height">
            <header id="top">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2">
                            <!-- Языки -->
                            <xsl:apply-templates select="locales" />
                            <!-- Логотип -->
                            <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="logo" />
                        </div>
                        <div class="col-xs-12 col-sm-10 text-right">
                            <!-- Номер телефона -->
                            <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="phone"/>
                            <!-- Авторизация-->
                            <div class="login">
                                <span class="glyphicon glyphicon-user"></span>
                                <xsl:text> </xsl:text>
                                <a role="dialog" data-target="#auth" data-remote="false" data-toggle="modal" href="{document('widget://users.authorization.loginLink')/result/url}"><xsl:value-of select="document('translate://project.site.users/Login')/result"/></a>
                                <xsl:text> / </xsl:text>
                                <a role="dialog" data-target="#reg" data-remote="false" data-toggle="modal" href="{document('widget://users.registration.link')/result/url}"><xsl:value-of select="document('translate://project.site.users/Registration')/result"/></a>
                            </div>
                            <!-- Меню в хедере -->
                            <xsl:apply-templates select="document('widget://structure.menu.auto?depth=1')" mode="headerMenu"/>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    </xsl:template>


</xsl:stylesheet>