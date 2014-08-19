<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Подключаем шаблоны модуля <Начало> -->
    <xsl:include href="template://module/news/index.news" />

    <xsl:include href="template://module/news/index.item" />
    <xsl:include href="template://module/news/index.rubric" />
    <xsl:include href="template://module/news/index.subject" />

    <xsl:include href="template://module/news/widget.item" />
    <xsl:include href="template://module/news/widget.rubric" />
    <xsl:include href="template://module/news/widget.subject" />

    <xsl:include href="template://module/news/page.item" />
    <xsl:include href="template://module/news/page.rubric" />
    <xsl:include href="template://module/news/page.subject" />
    <!-- Подключаем шаблоны модуля <Конец> -->

    <!-- Определяем часто используемые блоки <Начало> -->
    <xsl:template name="news.head">
        <div class="site-name">
            <div class="container-fluid">
                <span><xsl:value-of select="document('translate://project.site.blog/News')/result" /></span>
                <span class="sm"><xsl:apply-templates select="breadcrumbs" /></span>
            </div>
        </div>
    </xsl:template>

    <xsl:template name="news.sidebar">
        <div class="sidebar container-fluid">
            <div class="col-xs-12 col-sm-6 col-md-12">
                <h3><xsl:value-of select="document('translate://project.site.blog/Rubrics')/result" /></h3>
                <xsl:apply-templates select="document('widget://news.rubric.list?depth=2')" mode="news.sidebar.rss" />
            </div>
            <div class="col-xs-12 col-sm-6 col-md-12">
                <h3><xsl:value-of select="document('translate://project.site.blog/Subjects')/result" /></h3>
                <xsl:apply-templates select="document('widget://news.subject.list?depth=2')" mode="news.sidebar.list" />
            </div>
        </div>
    </xsl:template>
    <!-- Определяем часто используемые блоки <Конец> -->

</xsl:stylesheet>