<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Виджет админ тулбара -->
    <xsl:template match="result[@widget='topBar']">
        <xsl:value-of select="." disable-output-escaping="yes" />
    </xsl:template>

    <!-- Обработка ошибки -->
    <xsl:template match="result[@widget='topBar'][error]" />

</xsl:stylesheet>