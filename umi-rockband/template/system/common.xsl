<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">


    <!-- Языки в хедере <Начало> -->
    <xsl:template match="locales[locale]">
        <ul class="langs">
            <xsl:apply-templates select="locale" />
        </ul>
    </xsl:template>

    <xsl:template match="locale">
        <li>
            <a>
                <xsl:apply-templates select="." mode="locale.active" />
                <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
            </a>
            <xsl:apply-templates select="." mode="locale.separator" />
        </li>
    </xsl:template>

    <xsl:template match="locale" mode="locale.active">
        <xsl:attribute name="href"><xsl:value-of select="@url" /></xsl:attribute>
    </xsl:template>
    <xsl:template match="locale[@current = '1']" mode="locale.active">
        <xsl:attribute name="class">active</xsl:attribute>
    </xsl:template>
    <xsl:template match="locale"  mode="locale.separator" >
        <xsl:text>/</xsl:text>
    </xsl:template>
    <xsl:template match="locale[position() = last()]"  mode="locale.separator" />
    <!-- Языки в хедере <Конец> -->

    <!-- Значки социальных сетей <Начало> -->
    <xsl:template name="footerSocial">
        <ul class="social">
            <li><a href="#"><img src="/resources/umi-rockband/images/social/facebook.png" alt="" /></a></li>
            <li><a href="#"><img src="/resources/umi-rockband/images/social/twitter.png" alt="" /></a></li>
            <li><a href="#"><img src="/resources/umi-rockband/images/social/google.png" alt="" /></a></li>
        </ul>
    </xsl:template>
    <!-- Значки социальных сетей <Конец> -->

    <!-- Шаблон по умолчанию для виджетов <Начало> -->
    <xsl:template match="result">
        <textarea id="debug">
            <xsl:copy-of select="." />
        </textarea>
    </xsl:template>
    <!-- Шаблон по умолчанию для виджетов <Конец> -->

    <!-- Шаблон для вывода сообщений об ошибках <Начало> -->
    <xsl:template match="contents[error]">
        <div class="content-main">
            <div class="container-fluid">
                <div class="block">
                    <h3>
                        Ошибка:
                        <span style="color:red;">
                            <xsl:value-of select="error/@message" />
                        </span>
                    </h3>
                    <ul>
                        <xsl:apply-templates select="stack/item" mode="errorStackItem"/>
                    </ul>
                </div>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="errorStackItem">
        <li>
            <xsl:value-of select="concat(position(), '.: ')" />
            <xsl:value-of select="@message" />
        </li>
    </xsl:template>
    <!-- Шаблон для вывода сообщений об ошибках <Конец> -->

    <!-- Шаблон для вывода числовых значений полей <Начало> -->
    <xsl:template match="value[*]" mode="number">
        <xsl:value-of select="." />
    </xsl:template>

    <xsl:template match="value" mode="number">
        <xsl:text>0</xsl:text>
    </xsl:template>
    <!-- Шаблон для вывода числовых значений полей <Конец> -->

    <!-- Шаблон для вывода даты/времени в нужном формате <Начало> -->
    <xsl:template name="dateTime">
        <xsl:param name="timestamp" />
        <xsl:param name="format" />
        <xsl:param name="stringTime" />

        <xsl:choose>
            <xsl:when test="not($timestamp) and $stringTime">
                <xsl:variable name="resultTimestamp" select="php:functionString('strtotime', $stringTime)" />
                <xsl:value-of select="php:function('date', $format, $resultTimestamp)" />
            </xsl:when>
            <xsl:when test="$stringTime">
                <xsl:variable name="resultTimestamp" select="php:functionString('strtotime', $stringTime, $timestamp)" />
                <xsl:value-of select="php:function('date', $format, $resultTimestamp)" />
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="php:function('date', $format, $timestamp)" />
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>
    <!-- Шаблон для вывода даты/времени в нужном формат <Конец> -->

    <!-- Шаблоны для хлебных крошек <Начало> -->
    <xsl:template match="breadcrumbs">
        <xsl:apply-templates select="item" mode="breadcrumbs" />
    </xsl:template>

    <xsl:template match="item" mode="breadcrumbs">
        <a href="{url}"><xsl:value-of select="displayName" /></a>
        <xsl:text> / </xsl:text>
    </xsl:template>

    <xsl:template match="item[position() = last()]" mode="breadcrumbs">
        <a href="{url}"><xsl:value-of select="displayName" /></a>
    </xsl:template>

    <!-- Шаблоны для для хлебных крошек <Конец> -->

</xsl:stylesheet>