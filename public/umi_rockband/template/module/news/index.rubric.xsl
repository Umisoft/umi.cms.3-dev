<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница Рубрик <Начало> -->
    <xsl:template match="contents[@controller = 'news.rubric.index'][page]" >
        <xsl:call-template name="news.head" />

        <div class="content-inner">
            <div class="container-fluid">
                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="document('translate://project.site.news/Rubrics')/result"/>
                    </div>
                </div>
                <!-- Контент -->
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <xsl:apply-templates select="document('widget://news.rubric.list?options%5Bfields%5D=contents')" mode="news.content.list" />
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Страница Рубрик <Конец> -->

</xsl:stylesheet>