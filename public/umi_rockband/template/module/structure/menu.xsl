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
        <li>
            <a href="{page/@url}">
                <xsl:value-of select="page/@displayName"/>
            </a>
        </li>
    </xsl:template>
    <!-- Меню в хедере <Конец> -->

    <!-- Меню в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.custom']" mode="footer.content.root">
        <xsl:apply-templates select="tree/item" mode="footer.content.list"/>
    </xsl:template>

    <xsl:template match="item" mode="footer.content.list">
        <div class="col-md-3 text-center">
            <h5><a href="{@url}"><xsl:value-of select="@displayName"/></a></h5>
            <xsl:if test="item">
                <ul class="menu">
                    <xsl:apply-templates select="item" mode="footer.content.pages"/>
                </ul>
            </xsl:if>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="footer.content.pages">
        <li>
            <a href="{@url}">
                <xsl:value-of select="@displayName" />
            </a>
        </li>
    </xsl:template>
    <!-- Меню в футере <Конец> -->

</xsl:stylesheet>