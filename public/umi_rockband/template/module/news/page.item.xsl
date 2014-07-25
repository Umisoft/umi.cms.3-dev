<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей <Начало> -->
    <xsl:template match="contents[@controller='news.item.page']">
        <xsl:call-template name="news.head" />

        <div class="content-inner">
            <div class="container-fluid">
                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="//property[@name='metaTitle']/value" disable-output-escaping="yes" />
                    </div>
                    <div class="separating-line"></div>
                    <div class="caption">
                        <xsl:call-template name="dateTime">
                            <xsl:with-param name="format">d F Y</xsl:with-param>
                            <xsl:with-param name="stringTime" select="//property[@name='created']/value/date"/>
                        </xsl:call-template>
                    </div>
                </div>

                <!-- Контент -->
                <div class="content">
                    <xsl:value-of select="//property[@name='contents']/value" disable-output-escaping="yes" />
                </div>

            </div>
        </div>
    </xsl:template>
    <!-- Список новостей <Конец> -->

</xsl:stylesheet>