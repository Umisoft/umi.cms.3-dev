<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Пагинация <Начало> -->
    <xsl:template match="paginator">
        <div class="navigation navcent">
            <xsl:apply-templates select="previousPage"/>
            <ul class="pagination">
                <xsl:apply-templates select="firstPage[../currentPage/@number &gt; $pagesCount - 2]">
                    <xsl:with-param name="var" select="$pagesCount" />
                </xsl:apply-templates>
                <xsl:apply-templates select="range/page" mode="pagination"/>
                <xsl:apply-templates select="lastPage[../currentPage/@number &lt; @number - ($pagesCount - 3)]">
                    <xsl:with-param name="var" select="$pagesCount" />
                </xsl:apply-templates>
            </ul>
            <xsl:apply-templates select="nextPage"/>
        </div>
    </xsl:template>

    <xsl:template match="firstPage">
        <xsl:param name="var" />
        <li>
            <a href="{@url}">
                <xsl:value-of select="@number"/>
            </a>
        </li>
        <xsl:if test="../currentPage/@number != $var - 1">
            <li><span>...</span></li>
        </xsl:if>
    </xsl:template>

    <xsl:template match="lastPage">
        <xsl:param name="var" />
        <xsl:if test="../currentPage/@number != @number - ($var - 2)">
            <li><span>...</span></li>
        </xsl:if>
        <li>
            <a href="{@url}">
                <xsl:value-of select="@number"/>
            </a>
        </li>
    </xsl:template>

    <xsl:template match="previousPage">
        <a href="{@url}" class="np"><xsl:value-of select="document('translate://project.site/Previous')/result"/></a>
    </xsl:template>

    <xsl:template match="nextPage">
        <a href="{@url}" class="np"><xsl:value-of select="document('translate://project.site/Next')/result"/></a>
    </xsl:template>

    <xsl:template match="page" mode="pagination">
        <li>
            <a>
                <xsl:apply-templates select="." mode="pagination.current" />
                <xsl:value-of select="@number"/>
            </a>
        </li>
    </xsl:template>

    <xsl:template match="page[not(@current)]" mode="pagination.current">
        <xsl:attribute name="href"><xsl:value-of select="@url" /></xsl:attribute>
    </xsl:template>

    <xsl:template match="page[@current]" mode="pagination.current">
        <xsl:attribute name="class">active</xsl:attribute>
    </xsl:template>
    <!-- Пагинация <Конец> -->

</xsl:stylesheet>