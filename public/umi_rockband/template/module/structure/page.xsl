<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Статические страницы <Начало> -->
    <xsl:template match="contents[@controller = 'structure.index']" >
        <div class="site-name">
            <div class="container-fluid">
                <h1><xsl:value-of select="page/property[@name='h1']/value" disable-output-escaping="yes" /></h1>
                <span class="sm"><xsl:apply-templates select="breadcrumbs" /></span>
            </div>
        </div>
        <div class="content-inner">
            <div class="container-fluid content-page">
                <!-- Контент -->
                <xsl:value-of select="page/property[@name='contents']/value" disable-output-escaping="yes" />
            </div>
        </div>
    </xsl:template>
    <!-- Статические страницы <Конец> -->

</xsl:stylesheet>