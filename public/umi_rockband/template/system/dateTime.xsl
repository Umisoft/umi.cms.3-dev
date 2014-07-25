<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Шаблон для вывода даты/времени в нужном формате <Начало> -->
    <xsl:template name="dateTime">
        <xsl:param name="timestamp"/>
        <xsl:param name="format"/>
        <xsl:param name="stringTime"/>

        <xsl:choose>
            <xsl:when test="not($timestamp) and $stringTime">
                <xsl:variable name="resultTimestamp" select="php:functionString('strtotime', $stringTime)"/>
                <xsl:value-of select="php:function('date', $format, $resultTimestamp)"/>
            </xsl:when>
            <xsl:when test="$stringTime">
                <xsl:variable name="resultTimestamp" select="php:functionString('strtotime', $stringTime, $timestamp)"/>
                <xsl:value-of select="php:function('date', $format, $resultTimestamp)"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="php:function('date', $format, $timestamp)"/>
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>
    <!-- Шаблон для вывода даты/времени в нужном формат <Конец> -->

</xsl:stylesheet>