<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница постов автора <Начало> -->
    <xsl:template match="contents[@controller = 'blog.author.view.page'][page]" >
        <xsl:call-template name="blog.head" />
        <div class="content-inner">
            <div class="container-fluid">

                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="page/@h1" />
                    </div>
                    <div class="separating-line"></div>
                    <div class="caption">
                        <xsl:value-of select="document('translate://project.site/Authors%20posts')" />
                    </div>
                </div>

                <!-- Сайдбар -->
                <xsl:call-template name="blog.sidebar" />

                <!-- Вывод постов-->
                <div class="itemslist">
                    <xsl:apply-templates select="document(concat('widget://blog.author.view.posts?blogAuthors=', page/@guid,
                    '&amp;pagination%5BpageParam%5D=p&amp;pagination%5Btype%5D=elastic&amp;pagination%5BpagesCount%5D=',
                    $pagesCount, '&amp;limit=5'))" mode="blog.content.list" />
                </div>

            </div>
        </div>
    </xsl:template>
    <!-- Страница постов автора <Конец> -->

</xsl:stylesheet>