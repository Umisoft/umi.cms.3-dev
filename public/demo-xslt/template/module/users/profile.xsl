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

    <xsl:template match="result[@widget = 'users.profile.view'][code = 403]" mode="sideBar">

        <xsl:apply-templates select="document('widget://users.authorization.loginForm')/result"/>

        <p style="margin-top: 10px">
            <xsl:apply-templates select="document('widget://users.registration.link')/result"/>
            <xsl:apply-templates select="document('widget://users.restoration.link')/result"/>
        </p>
    </xsl:template>


</xsl:stylesheet>