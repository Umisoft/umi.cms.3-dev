<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница постов с тегом <Начало> -->
    <xsl:template match="contents[@controller = 'blog.tag.page'][page]" >
        <xsl:call-template name="blog.head" />
        <div class="content-inner">
            <div class="container-fluid">

                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="page/@h1" />
                    </div>
                </div>

                <!-- Вывод постов-->
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <xsl:apply-templates select="document(concat('widget://blog.tag.postList?tags=', page/@guid,
                    '&amp;pagination%5BpageParam%5D=p&amp;pagination%5Btype%5D=elastic&amp;pagination%5BpagesCount%5D=',
                    $pagesCount, '&amp;limit=5'))" mode="blog.content.list" />
                </div>

                <!-- Сайдбар -->
                <div class="col-xs-6 col-md-4">
                    <xsl:call-template name="blog.sidebar" />
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Страница постов с тегом <Конец> -->

    <!-- Вывод тега <Начало> -->
    <xsl:template match="item" mode="blog.tags.list">
        <a href="{@url}"><xsl:value-of select="@displayName" /></a>
    </xsl:template>
    <!-- Вывод тега <Конец> -->

</xsl:stylesheet>