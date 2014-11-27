<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Шаблон формы поиска <Начало> -->
    <xsl:template match="result[@widget = 'search.form']" mode="search.head.form">
        <li class="search">
            <form class="h-search-form">
                <xsl:apply-templates select="form/attributes" mode="all.form"/>
                <div class="input-group input-group-sm">
                    <span class="input-group-btn">
                        <button class="btn" type="button">
                            <img src="images/search-icon.png" width="12" height="12"/>
                        </button>
                    </span>
                    <xsl:apply-templates select="form/elements/item[attributes/@name='query']" mode="search.form" />
                </div>
            </form>
        </li>
    </xsl:template>
    <!-- Шаблон формы поиска <Конец> -->

    <!-- Шаблон результатов поиска -->
    <xsl:template match="result[@widget = 'search.results']" mode="search.content.result">
        <h5>
            <xsl:value-of select="document('translate://project.site.search/Found%20partOne')/result"/>
            <xsl:text> "</xsl:text>
            <xsl:value-of select="query"/>
            <xsl:text>" </xsl:text>
            <xsl:choose>
                <xsl:when test="list/collection/item">
                    <xsl:value-of select="document('translate://project.site.search/Found%20partTwo')/result"/>
                </xsl:when>
                <xsl:otherwise><xsl:value-of select="document('translate://project.site.search/No%20result')/result"/></xsl:otherwise>
            </xsl:choose>
        </h5>
        <ul>
            <xsl:apply-templates select="list/collection/item" mode="search.content.result">
                <xsl:with-param name="query" select="encodedQuery"/>
            </xsl:apply-templates>
        </ul>
        <xsl:apply-templates select="paginator" />
    </xsl:template>

    <xsl:template match="item" mode="search.content.result">
        <xsl:param name="query"/>
        <li>
            <a href="{indexedObject/@url}">
                <xsl:value-of select="indexedObject/@displayName"/>
            </a>
            <div>
                <xsl:apply-templates select="document(concat('widget://search.fragments?query=', $query, '&amp;index=', @guid))"
                                     mode="search.content.result"/>
            </div>
        </li>
    </xsl:template>

    <!-- Шаблон контекста поискового результата -->
    <xsl:template match="result[@widget = 'search.fragments']" mode="search.content.result">
        <xsl:apply-templates select="fragmenter/item" mode="search.content.fragment" />
    </xsl:template>

    <xsl:template match="item" mode="search.content.fragment" />
    <xsl:template match="item[edgeRight/item]" mode="search.content.fragment">
        <p>
            <xsl:apply-templates select="edgeLeft/item" mode="search.content.edge" />
            <mark><xsl:value-of select="center" /></mark>
            <xsl:apply-templates select="edgeRight/item" mode="search.content.edge" />
        </p>
    </xsl:template>

    <xsl:template match="item" mode="search.content.edge">
        <xsl:value-of select="concat(., ' ')" />
    </xsl:template>

</xsl:stylesheet>