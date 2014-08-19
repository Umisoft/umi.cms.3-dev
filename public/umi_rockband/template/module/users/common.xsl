<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Подключаем шаблоны модуля <Начало> -->
    <xsl:include href="template://module/users/index.registration" />
    <xsl:include href="template://module/users/login.authorization" />
    <xsl:include href="template://module/users/widget.registration" />
    <!-- Подключаем шаблоны модуля <Конец> -->

    <!-- Определяем часто используемые блоки <Начало> -->
    <xsl:template name="users.head">
        <div class="site-name">
            <div class="container-fluid">
                <span><xsl:value-of select="page/@displayName" /></span>
                <span class="sm"><xsl:apply-templates select="breadcrumbs" /></span>
            </div>
        </div>
    </xsl:template>
    <!-- Определяем часто используемые блоки <Конец> -->

</xsl:stylesheet>