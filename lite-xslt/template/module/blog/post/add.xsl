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

    <xsl:template match="contents[@controller='blog.post.add.index']" mode="layout">

        <xsl:apply-templates select="breadcrumbs" mode="layout"/>
        <xsl:apply-templates select="page" mode="layout"/>

        <xsl:apply-templates select="form"/>

    </xsl:template>

    <xsl:template match="contents[@controller='blog.post.add.index'][added = 1]" mode="layout">

        <xsl:apply-templates select="breadcrumbs" mode="layout"/>
        <xsl:apply-templates select="page" mode="layout"/>

        <div class="alert alert-success">
            <p class="bg-success text-success">
                <xsl:value-of select="document('translate://project.site.blog.post.add/PostHasBeenAdded')/result"/>
            </p>
            <p class="bg-success text-success">
                <a href="{blogPost/@url}" class="alert-link">
                    <xsl:value-of select="document('translate://project.site/ReadMore')/result"/>
                </a>
            </p>
        </div>


    </xsl:template>

</xsl:stylesheet>