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

    <!-- Меню в хедере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="headerMenu">
        <ul class="menu">
            <xsl:apply-templates select="menu/item" mode="headerMenu"/>
            <li class="search">
                <form class="h-search-form">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><img src="/resources/umi-rockband/images/search-icon.png" width="12" height="12" /></button>
                        </span>
                        <input type="text" class="form-control" />
                    </div>
                </form>
            </li>
        </ul>
    </xsl:template>

    <xsl:template match="item" mode="headerMenu">
        <li><a href="{page/@url}">
            <xsl:value-of select="page/@displayName"/>
        </a></li>
    </xsl:template>
    <!-- Меню в хедере <Конец> -->

    <!-- Меню в футере <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.auto']" mode="footerMenu">
        <xsl:apply-templates select="menu/item" mode="footerMenu"/>
    </xsl:template>

    <xsl:template match="item" mode="footerMenu">
        <div class="col-md-2">
            <h5><xsl:value-of select="page/@displayName"/></h5>
            <ul class="menu">
                <xsl:apply-templates select="children/item" mode="footerMenuLevel2"/>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="footerMenuLevel2">
        <li><a href="page/@url">
            <xsl:value-of select="page/@displayName" />
        </a></li>
    </xsl:template>
    <!-- Меню в футере <Конец> -->

    <!-- Шаблон для custom меню <Начало> -->
    <xsl:template match="result[@widget = 'structure.menu.custom'][tree/item]" mode="bottomMenu">
        <ul class="bottomMenu">
            <xsl:apply-templates select="tree/item" mode="bottomMenu"/>
        </ul>
    </xsl:template>

    <xsl:template match="tree/item" mode="bottomMenu">
        <li>
            <a href="{@url}">
                <xsl:if test="@type='externalItem'">
                    <xsl:attribute name="target">blank</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@displayName" />
            </a>
        </li>
    </xsl:template>
    <!-- Шаблон для custom меню <Конец> -->

</xsl:stylesheet>