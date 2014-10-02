<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Подключаем шаблоны модуля <Начало> -->
    <xsl:include href="template://module/blog/index.blog" />

    <xsl:include href="template://module/blog/widget.category" />
    <xsl:include href="template://module/blog/widget.post" />
    <xsl:include href="template://module/blog/widget.tag" />
    <xsl:include href="template://module/blog/widget.author" />
    <xsl:include href="template://module/blog/widget.comment" />

    <xsl:include href="template://module/blog/page.category" />
    <xsl:include href="template://module/blog/page.post" />
    <xsl:include href="template://module/blog/page.tag" />
    <xsl:include href="template://module/blog/page.author" />
    <!-- Подключаем шаблоны модуля <Конец> -->

    <!-- Определяем часто используемые блоки <Начало> -->
    <xsl:template name="blog.head">
        <div class="site-name">
            <div class="container-fluid">
                <span><xsl:value-of select="document('translate://project.site.blog/Blog')/result" /></span>
                <span class="sm"><xsl:apply-templates select="breadcrumbs"/></span>
            </div>
        </div>
    </xsl:template>

    <xsl:template name="blog.sidebar">
        <div class="sidebar container-fluid">
            <div class="col-xs-12 col-sm-4 col-md-12">
                <h3><a href="#"><xsl:value-of select="document('translate://project.site/Categories')/result" /></a></h3>
                <xsl:apply-templates select="document('widget://blog.category.list')" mode="blog.sidebar.list" />
            </div>
            <div class="col-xs-12 col-sm-4 col-md-12">
                <h3><a href="#"><xsl:value-of select="document('translate://project.site/Authors')/result" /></a></h3>
                <xsl:apply-templates select="document('widget://blog.author.view.list')" mode="blog.sidebar.list" />
            </div>
            <div class="col-xs-12 col-sm-4 col-md-12">
                <h3><a href="#"><xsl:value-of select="document('translate://project.site/Tags')/result" /></a></h3>
                <xsl:apply-templates select="document('widget://blog.tag.list')" mode="blog.sidebar.list" />
            </div>
        </div>
    </xsl:template>
    <!-- Определяем часто используемые блоки <Конец> -->

</xsl:stylesheet>