<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Шаблон по умолчанию для виджетов <Начало> -->
    <xsl:template match="result[error]">
        <xsl:comment><xsl:value-of select="error/@message" /></xsl:comment>
    </xsl:template>
    <!-- Шаблон по умолчанию для виджетов <Конец> -->

</xsl:stylesheet>