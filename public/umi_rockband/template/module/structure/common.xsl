<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:include href="template://module/structure/menu" />
    <xsl:include href="template://module/structure/infoblock" />
    <xsl:include href="template://module/structure/page" />
    <xsl:include href="template://module/structure/index" />
    <xsl:include href="template://module/structure/header" />
    <xsl:include href="template://module/structure/footer" />

</xsl:stylesheet>