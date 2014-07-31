<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Объявляем переменные -->
    <xsl:variable name="template" select="/layout/assetsUrl" />
    <xsl:variable name="root" select="/layout/projectUrl/locale[@current = 1]/@url" />
    <xsl:variable name="pagesCount" select="'5'" />

    <!-- Подключаем шаблоны модулей <Начало> -->
    <xsl:include href="template://module/structure/common" />
    <xsl:include href="template://module/news/common" />
    <xsl:include href="template://module/blog/common" />
    <xsl:include href="template://module/search/common" />
    <!-- Подключаем шаблоны модулей <Конец> -->

    <!-- Подключаем дополнительные шаблоны <Начало> -->
    <xsl:include href="template://error/widget" />
    <xsl:include href="template://system/breadcrumbs" />
    <xsl:include href="template://system/dateTime" />
    <xsl:include href="template://system/paginator" />
    <xsl:include href="template://system/topBar" />
    <xsl:include href="template://system/form" />
    <!-- Подключаем дополнительные шаблоны <Конец> -->

    <!-- Переключение языковой версии <Начало> -->
    <xsl:template match="locales[locale]">
        <ul class="langs">
            <xsl:apply-templates select="locale"/>
        </ul>
    </xsl:template>

    <xsl:template match="locale">
        <li>
            <a>
                <xsl:apply-templates select="." mode="locale.active"/>
                <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
            </a>
            <xsl:apply-templates select="." mode="locale.separator"/>
        </li>
    </xsl:template>

    <xsl:template match="locale" mode="locale.active">
        <xsl:attribute name="href">
            <xsl:value-of select="@url"/>
        </xsl:attribute>
    </xsl:template>
    <xsl:template match="locale[@current = '1']" mode="locale.active">
        <xsl:attribute name="class">active</xsl:attribute>
    </xsl:template>
    <xsl:template match="locale" mode="locale.separator">
        <xsl:text>/</xsl:text>
    </xsl:template>
    <xsl:template match="locale[position() = last()]" mode="locale.separator"/>
    <!-- Переключение языковой версии <Конец> -->

    <!-- Шаблон для вывода сообщений об ошибках на странице <Начало> -->
    <xsl:template match="contents[error]">
        <div class="content-main">
            <div class="container-fluid">
                <div class="block">
                    <h3>
                        <xsl:value-of select="document('translate://project.site/Error')/result" />
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="code" />:
                        <xsl:text> </xsl:text>
                        <span style="color:red;">
                            <xsl:value-of select="error/@message"/>
                        </span>
                    </h3>
                    <textarea><xsl:value-of select="//trace" disable-output-escaping="yes" /></textarea>
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Шаблон для вывода сообщений об ошибках на странице <Конец> -->

    <!-- Шаблон для вывода числовых значений полей <Начало> -->
    <xsl:template match="value" mode="number">
        <xsl:text>0</xsl:text>
    </xsl:template>

    <xsl:template match="value[node()]" mode="number">
        <xsl:value-of select="."/>
    </xsl:template>
    <!-- Шаблон для вывода числовых значений полей <Конец> -->

</xsl:stylesheet>