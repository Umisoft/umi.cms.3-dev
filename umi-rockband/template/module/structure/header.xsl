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

    <xsl:template name="header">
        <header id="top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-2">
                        <!-- Языки -->
                        <xsl:apply-templates select="locales" />
                        <!-- Логотип -->
                        <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="logo"/>
                    </div>
                    <div class="right col-xs-12 col-sm-9 col-md-10 text-right">
                        <!-- Номер телефона -->
                        <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="phone"/>
                        <br />
                        <!-- Меню в хедере -->
                        <xsl:apply-templates select="document('widget://structure.menu.auto?depth=1')" mode="headerMenu"/>
                    </div>
                </div>
            </div>
        </header>
    </xsl:template>


</xsl:stylesheet>