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



    <xsl:template match="result[@widget = 'users.profile.link']">
        <a class="btn btn-primary" role="button" href="{url}">
            <xsl:value-of select="document('translate://project.site.users.profile/EditProfile')/result"/>
        </a>
    </xsl:template>

    <xsl:template match="result[@widget = 'users.profile.password.link']">
        <a class="btn btn-primary" role="button" href="{url}">
            <xsl:value-of select="document('translate://project.site.users.profile.password/ChangePassword')/result"/>
        </a>
    </xsl:template>

    <xsl:template match="result[@widget = 'users.profile.view'][code = 403]" mode="sideBar">

        <xsl:apply-templates select="document('widget://users.authorization.loginForm')/result"/>

        <p style="margin-top: 10px">
            <xsl:apply-templates select="document('widget://users.registration.link')/result"/>
            <xsl:apply-templates select="document('widget://users.restoration.link')/result"/>
        </p>
    </xsl:template>

    <xsl:template match="result[@widget = 'users.profile.view'][user]" mode="sideBar">
        <p>
            <xsl:value-of select="document('translate://project.site.users.profile/Welcome')/result"/>, <xsl:value-of select="user/@displayName"/>
            <xsl:apply-templates select="document('widget://users.authorization.logoutForm')/result"/>
            <xsl:apply-templates select="document('widget://users.profile.link')/result"/>
        </p>
    </xsl:template>

    <xsl:template match="contents[@controller='users.profile.index']" mode="layout">

        <xsl:apply-templates select="breadcrumbs" mode="layout"/>

        <xsl:apply-templates select="page" mode="layout"/>

        <xsl:apply-templates select="errors" mode="formErrors"/>

        <xsl:apply-templates select="form"/>

        <xsl:apply-templates select="document('widget://users.profile.password.link')/result"/>
    </xsl:template>

    <xsl:template match="contents[@controller='users.profile.password.index']" mode="layout">

        <xsl:apply-templates select="breadcrumbs" mode="layout"/>

        <xsl:apply-templates select="page" mode="layout"/>

        <xsl:apply-templates select="errors" mode="formErrors"/>

        <xsl:apply-templates select="form"/>
    </xsl:template>

</xsl:stylesheet>