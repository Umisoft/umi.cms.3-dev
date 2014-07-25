<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Пагинация <Начало> -->
    <xsl:template match="paginator">
        <xsl:variable name="var" select="'5'" />
        <div class="navigation navcent">
            <xsl:apply-templates select="previousPage"/>
            <ul class="pagination">
                <xsl:apply-templates select="firstPage[../currentPage/@number &gt; $var - 2]">
                    <xsl:with-param name="var" select="$var" />
                </xsl:apply-templates>
                <xsl:apply-templates select="range/page" mode="pagination"/>
                <xsl:apply-templates select="lastPage[../currentPage/@number &lt; @number - ($var - 3)]">
                    <xsl:with-param name="var" select="$var" />
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
        <a href="{@url}" class="np">Назад</a>
    </xsl:template>

    <xsl:template match="nextPage">
        <a href="{@url}" class="np">Вперед</a>
    </xsl:template>

    <xsl:template match="page" mode="pagination">
        <li>
            <a href="{@url}">
                <xsl:apply-templates select="@current"/>
                <xsl:value-of select="@number"/>
            </a>
        </li>
    </xsl:template>

    <xsl:template match="@current">
        <xsl:attribute name="class">active</xsl:attribute>
    </xsl:template>
    <!-- Пагинация <Конец> -->

</xsl:stylesheet>