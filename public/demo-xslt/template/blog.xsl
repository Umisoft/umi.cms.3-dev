<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:output
            encoding="utf-8"
            method="html"
            indent="yes"
            cdata-section-elements="script noscript"
            omit-xml-declaration="yes"
            doctype-system="about:legacy-compat"
            />

    <xsl:variable name="anchor" select="concat(/layout/url, '/#')" />

    <xsl:include href="template://blogTemplate" />
    <xsl:include href="template://common/common" />
    <xsl:include href="template://common/form" />

    <xsl:include href="template://module/structure/components" />
    <xsl:include href="template://module/users/components" />

    <xsl:include href="template://module/blog/components" />

</xsl:stylesheet>