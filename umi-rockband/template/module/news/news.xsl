<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Страница новостей <Начало> -->
    <xsl:template match="contents[@controller = 'news.item.index'][page]" >
        <div class="site-name">
            <div class="container-fluid">
                <span>Новости</span>
                <span class="sm"><xsl:apply-templates select="breadcrumbs" /></span>
            </div>
        </div>

        <div class="content-inner">
            <div class="container-fluid">
                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="document('translate://project.site.news/RecentEvents')/result"/>
                    </div>
                    <div class="separating-line"></div>
                    <div class="caption">
                        <xsl:value-of select="document('translate://project.site.news/LatestNews24Hours')/result"/>
                    </div>
                </div>
                <!-- Контент -->
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <xsl:apply-templates select="document('widget://news.item.list')" />
                </div>
                <!-- Сайдбар -->
                <div class="col-xs-6 col-md-4">
                    <div class="sidebar">
                        <h3><a>Рубрики</a></h3>
                        <xsl:apply-templates select="document('widget://news.rubric.list?depth=2')" mode="news.sidebar.rss" />
                        <h3><a>Сюжеты</a></h3>
                        <xsl:apply-templates select="document('widget://news.subject.list?depth=2')" mode="news.sidebar.rss" />
                        <div class="clear"></div>
                    </div>

                </div>

                <div class="clear"></div>

                <!-- Пагинация -->

                <div class="navigation navcent">
                    <a href="#" class="np">Назад</a>
                    <ul class="pagination">
                        <li><a href="#">1</a></li>
                        <li><a href="#" class="active">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><span>...</span></li>
                        <li><a href="#">100</a></li>
                    </ul>
                    <a href="#" class="np">Вперед</a>
                </div>

            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>