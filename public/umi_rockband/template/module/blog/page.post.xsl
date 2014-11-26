<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Список постов <Начало> -->
    <xsl:template match="contents[@controller='blog.post.view.page']">
        <xsl:call-template name="blog.head"/>
        <div class="content-inner">
            <div class="container-fluid">
                <!-- Заголовок -->
                <div class="page-title">
                    <div class="title">
                        <xsl:value-of select="page/@h1" disable-output-escaping="yes"/>
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
                    <div class="col-xs-12 col-sm-4">
                        <div class="userinfo">
                            <div class="author">
                                <xsl:value-of select="document('translate://project.site.blog/Author')" />
                            </div>
                            <div class="name">
                                <xsl:value-of select="//property[@name='author']/value/@displayName"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="title-top"><xsl:value-of select="document('translate://project.site/Tags')" /></div>
                        <div class="tags">
                            <xsl:apply-templates select="//property[@name='tags']/value/item" mode="blog.tags.list"/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 share">
                        <div class="title-top"><xsl:value-of select="document('translate://project.site.blog/Share')" /></div>
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

                <div id="comments">
                    <h3><xsl:value-of select="document('translate://project.site.blog/Comments')/result" /> - <xsl:value-of select="//property[@name='commentsCount']/value" /></h3>
                    <textarea>
                        <xsl:copy-of select="document(concat('widget://blog.comment.list?blogPost=', page/@guid, '&amp;options%5Bfields%5D=contents,publishTime'))" />
                    </textarea>
                    <xsl:apply-templates select="document(concat('widget://blog.comment.list?blogPost=', page/@guid, '&amp;options%5Bfields%5D=contents,publishTime'))" mode="blog.comments.list">
                        <xsl:with-param name="postGuid" select="page/@guid" />
                    </xsl:apply-templates>
                    <xsl:apply-templates select="document(concat('widget://blog.comment.add.addForm?blogPost=', page/@guid))" mode="blog.comments.form" />
                </div>
            </div>
        </div>
    </xsl:template>
    <!-- Список постов <Конец> -->

</xsl:stylesheet>