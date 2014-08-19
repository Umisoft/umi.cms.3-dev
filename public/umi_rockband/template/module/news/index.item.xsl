<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница всех новостей <Начало> -->
    <xsl:template match="contents[@controller = 'news.item.index'][page]" >
        <xsl:call-template name="news.head" />

        <div class="content-inner">
            <div class="container-fluid">

                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="document('translate://project.site.news/RecentEvents')/result"/>
                    </div>
                    <div class="separating-line"></div>
                    <div class="caption">
                        <xsl:value-of select="document('translate://project.site.news/LatestNews')/result"/>
                    </div>
                </div>

                <!-- Сайдбар -->
                <xsl:call-template name="news.sidebar" />

                <!-- Контент -->
                <div class="itemslist">
                    <xsl:apply-templates select="document(concat('widget://news.item.list?pagination%5BpageParam%5D=p&amp;pagination%5Btype%5D=elastic&amp;pagination%5BpagesCount%5D=',
                    $pagesCount, '&amp;limit=5'))" />
                </div>

            </div>
        </div>
    </xsl:template>
    <!-- Страница всех новостей <Конец> -->

</xsl:stylesheet>