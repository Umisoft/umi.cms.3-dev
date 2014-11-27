<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница регистрации <Начало> -->
    <xsl:template match="contents[@controller = 'users.authorization.login'][page]" >
        <xsl:call-template name="users.head" />

        <div class="content-inner">
            <div class="container-fluid">
                <!-- Контент -->
                <xsl:apply-templates select="errors" mode="default.form" />
                <xsl:apply-templates select="form" mode="default.form" />
            </div>
        </div>
    </xsl:template>
    <!-- Страница регистрации <Конец> -->

</xsl:stylesheet>