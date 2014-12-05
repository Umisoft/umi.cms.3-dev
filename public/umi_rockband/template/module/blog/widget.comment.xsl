<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Форма добавления комментария <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.add.addForm']" mode="blog.comments.form">
        <h3>
            <xsl:value-of select="document('translate://project.site.blog/Add%20comment')/result"/>
        </h3>
        <xsl:apply-templates select="form" mode="default.form">
            <xsl:with-param name="css.class">col-sm-12 col-md-6</xsl:with-param>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.comment.add.addForm'][error]" mode="blog.comments.form" />
    <!-- Форма добавления комментария <Конец> -->

    <!-- Кнопка "ответить" на комментарий <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.add.addForm']" mode="blog.comments.replyTo">
        <button class="btn btn-sm btn-primary" name="re:{blogComment/@displayName}" value="{form/attributes/@action}">
            <xsl:value-of select="document('translate://project.site.blog/Reply')/result"/>
        </button>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.comment.add.addForm'][error]" mode="blog.comments.replyTo" />
    <!-- Кнопка "ответить" на комментарий <Конец> -->

    <!-- Список комментариев <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.tree']/tree" mode="blog.comments.list">
        <xsl:param name="postGuid"/>
        <xsl:apply-templates select="item" mode="blog.comments.list">
            <xsl:with-param name="postGuid" select="$postGuid"/>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item" mode="blog.comments.list">
        <xsl:param name="postGuid"/>
        <section class="comment">
            <div class="head">
                <h5>
                    <xsl:value-of select="@displayName"/>
                </h5>
                <span class="date">
                    <xsl:call-template name="dateTime">
                        <xsl:with-param name="format">d F Y в H:i</xsl:with-param>
                        <xsl:with-param name="stringTime" select=".//property[@name='publishTime']/value/date"/>
                    </xsl:call-template>
                </span>
            </div>

            <p>
                <xsl:value-of select=".//property[@name='contents']/value" disable-output-escaping="yes"/>
                <xsl:apply-templates
                        select="document(concat('widget://blog.comment.add.addForm?blogPost=', $postGuid, '&amp;blogComment=', @guid))"
                        mode="blog.comments.replyTo"/>
            </p>

            <xsl:apply-templates select="item" mode="blog.comments.list">
                <xsl:with-param name="postGuid" select="$postGuid"/>
            </xsl:apply-templates>
        </section>

    </xsl:template>
    <!-- Список комментариев <Конец> -->


</xsl:stylesheet>