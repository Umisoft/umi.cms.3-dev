<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:template match="result[@widget = 'users.profile.view']" mode="users.head.link">
        <!--xsl:choose>
            <xsl:when test="//property[@name='firstName']/value">
                <span><xsl:value-of select="//property[@name='firstName']/value" /> <xsl:value-of select="//property[@name='lastName']/value" /></span>
            </xsl:when>
            <xsl:otherwise>
                <span><xsl:value-of select="//property[@name='login']/value" /></span>
            </xsl:otherwise>
        </xsl:choose -->
    </xsl:template>

    <xsl:template match="result[@widget = 'users.profile.view'][error]" mode="users.head.link">
        <div class="login">
            <span class="glyphicon glyphicon-user"></span>
            <xsl:text> </xsl:text>
            <a role="dialog" data-target="#auth" data-remote="false" data-toggle="modal"
               href="{document('widget://users.authorization.loginLink')/result/url}">
                <xsl:value-of select="document('translate://project.site.users/Login')/result"/>
            </a>
            <xsl:text> / </xsl:text>
            <a role="dialog" data-target="#reg" data-remote="false" data-toggle="modal" href="{document('widget://users.registration.link')/result/url}">
                <xsl:value-of select="document('translate://project.site.users/Registration')/result"/>
            </a>
        </div>
    </xsl:template>

</xsl:stylesheet>