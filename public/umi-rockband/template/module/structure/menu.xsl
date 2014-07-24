<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Меню в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="headerMenu">
        <ul class="menu">
            <xsl:apply-templates select="menu/item" mode="headerMenu" />
            <xsl:apply-templates select="document('widget://search.form')" mode="search.head.form" />
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="headerMenu">
        <li><a href="{page/@url}">
            <xsl:value-of select="page/@displayName"/>
        </a></li>
    </xsl:template>
    <!-- Меню в хедере <Конец> -->

    <!-- Меню в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="footer.content.root">
        <xsl:apply-templates select="menu/item" mode="footer.content.list"/>
    </xsl:template>

    <xsl:template match="item" mode="footer.content.list">
        <div class="col-md-3">
            <h5><a href="{page/@url}"><xsl:value-of select="page/@displayName"/></a></h5>
            <ul class="menu">
                <xsl:apply-templates select="children/item" mode="footer.content.pages"/>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="footer.content.pages">
        <li>
            <a href="{page/@url}">
                <xsl:value-of select="page/@displayName" />
            </a>
        </li>
    </xsl:template>
    <!-- Меню в футере <Конец> -->

</xsl:stylesheet>