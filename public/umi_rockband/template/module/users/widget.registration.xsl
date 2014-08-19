<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:template match="result[@widget = 'users.registration.link']" mode="users.head.link">
        <xsl:value-of select="url" />
    </xsl:template>


</xsl:stylesheet>