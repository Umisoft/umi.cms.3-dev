<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список новостей <Начало> -->
    <xsl:template match="contents[@controller='blog.post.view.page']">
        <xsl:call-template name="blog.head"/>
        <div class="content-inner">
            <div class="container-fluid">
                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="//property[@name='h1']/value" disable-output-escaping="yes"/>
                    </div>
                    <div class="separating-line"></div>
                    <div class="caption">
                        <xsl:call-template name="dateTime">
                            <xsl:with-param name="format">d F Y</xsl:with-param>
                            <xsl:with-param name="stringTime" select="//property[@name='publishTime']/value/date"/>
                        </xsl:call-template>
                    </div>
                </div>

                <!-- Контент -->
                <div class="row top-info">
                    <div class="col-md-4">
                        <div class="userinfo">
                            <div class="name">
                                <xsl:value-of select="//property[@name='author']/value/@displayName"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <!--TODO Label please-->
                        <div class="title-top">Теги</div>
                        <div class="tags">
                            <xsl:apply-templates select="//property[@name='tags']/value/item" mode="blog.tags.list"/>
                        </div>
                    </div>
                    <div class="col-md-3 share">
                        <!--TODO Label please-->
                        <div class="title-top">Поделитесь с друзьями</div>
                        <a href="#" class="vk">
                            <i></i>
                        </a>
                        <a href="#" class="fb">
                            <i></i>
                        </a>
                        <a href="#" class="twitter">
                            <i></i>
                        </a>
                        <a href="#" class="g">
                            <i></i>
                        </a>
                    </div>
                </div>

                <div class="content">
                    <xsl:value-of select="//property[@name='contents']/value" disable-output-escaping="yes"/>
                </div>

                <div class="comment">
                    <div class="com-title"><xsl:value-of select="document('translate://project.site.blog/Comments')/result" /> - <xsl:value-of select="//property[@name='commentsCount']/value" /></div>
                    <xsl:apply-templates select="document(concat('widget://blog.comment.add.addForm?blogPost=', page/@guid))" mode="blog.comments.form" />
                    <xsl:apply-templates select="document(concat('widget://blog.comment.list?blogPost=', page/@guid, '&amp;options%5Bfields%5D=contents,publishTime'))" mode="blog.comments.list">
                        <xsl:with-param name="postGuid" select="page/@guid" />
                    </xsl:apply-templates>

                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Список новостей <Конец> -->

</xsl:stylesheet>