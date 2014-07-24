<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница поиска <Начало> -->
    <xsl:template match="contents[@controller = 'search.search']">
        <div class="site-name">
            <div class="container-fluid">
                <h1><xsl:value-of select="document('translate://project.site.search/HSearch')/result"/></h1>
                <span class="sm">
                    <xsl:apply-templates select="breadcrumbs"/>
                </span>
            </div>
        </div>
        <div class="content-inner">
            <div class="container-fluid content-page">
                <!-- Контент -->
                <div class="col-md-offset-3 col-md-6 text-center">
                    <p class="text-center"><xsl:value-of select="document('translate://project.site.search/No%20results')/result"/></p>
                    <form class="form-search">
                        <xsl:apply-templates select="form/attributes/@*" mode="form"/>
                        <div class="input-group">
                            <xsl:apply-templates select="form/elements/item[id='query']" mode="form">
                                <xsl:with-param name="class">form-control</xsl:with-param>
                            </xsl:apply-templates>
                            <div class="input-group-btn">
                                <xsl:apply-templates select="form/elements/item[type='submit']" mode="form">
                                    <xsl:with-param name="class">btn btn-default-font btn-primary</xsl:with-param>
                                </xsl:apply-templates>
                            </div>
                        </div>
                    </form>
                </div>
                <hr class="col-md-12" />
                <div class="col-md-12">
                    <!-- Результаты поиска -->
                    <xsl:apply-templates select="document(concat('widget://search.results?query=', encodedQuery,
                        '&amp;pagination%5BpageParam%5D=p&amp;pagination%5Btype%5D=elastic&amp;pagination%5BpagesCount%5D=', $pagesCount,
                        '&amp;limit=5'))" mode="search.content.result" />
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Страница поиска <Конец> -->

</xsl:stylesheet>