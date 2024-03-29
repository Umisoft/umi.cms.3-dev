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

    <xsl:template match="result[@widget = 'users.authorization.loginForm']">
        <xsl:apply-templates select="form"/>
    </xsl:template>

    <xsl:template match="contents[@controller='users.authorization.login']" mode="layout">
        <xsl:apply-templates select="breadcrumbs" mode="layout"/>

        <xsl:apply-templates select="page" mode="layout"/>

        <xsl:apply-templates select="errors" mode="formErrors"/>

        <xsl:apply-templates select="form"/>

    </xsl:template>

</xsl:stylesheet>