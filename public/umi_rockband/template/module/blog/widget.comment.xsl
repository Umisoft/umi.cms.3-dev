<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Форма добавления комментария <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.add.addForm']" mode="blog.comments.form">
        <form>
            <xsl:apply-templates select="form/attributes/@*" mode="form"/>
            <xsl:apply-templates select="form/elements/item[type='hidden']" mode="form"/>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><xsl:value-of select="form/elements/item[id='displayName']/label" />:</label>
                        <xsl:apply-templates select="form/elements/item[id='displayName']" mode="form">
                            <xsl:with-param name="class">form-control</xsl:with-param>
                        </xsl:apply-templates>
                    </div>
                    <div>
                        <label><xsl:value-of select="form/elements/item[type='textarea']/label" />:</label>
                        <xsl:apply-templates select="form/elements/item[type='textarea']" mode="form">
                            <xsl:with-param name="class">field-com</xsl:with-param>
                        </xsl:apply-templates>
                    </div>
                    <div class="tools">
                        <div class="com-line"></div>
                    </div>
                </div>
                <xsl:apply-templates select="form/elements/item[type='submit']" mode="form">
                    <xsl:with-param name="class">btn btn-custom btn-default-font btn-noshadow btn-primary</xsl:with-param>
                </xsl:apply-templates>
            </div>
        </form>
    </xsl:template>
    <!-- Форма добавления комментария <Конец> -->

    <!-- Кнопка "ответить" на комментарий <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.add.addForm']" mode="blog.comments.replyTo">
        <button name="re:{blogComment/@displayName}" value="{form/attributes/@action}" class="btn btn-custom btn-default-font btn-noshadow btn-primary">Ответить</button>
     </xsl:template>
    <!-- Кнопка "ответить" на комментарий <Конец> -->

    <!-- Список комментариев <Начало> -->
    <xsl:template match="result[@widget = 'blog.comment.list']/tree" mode="blog.comments.list">
        <xsl:param name="postGuid"/>
        <xsl:apply-templates select="item" mode="blog.comments.list">
            <xsl:with-param name="postGuid" select="$postGuid"/>
            <xsl:with-param name="parents" select="0"/>
        </xsl:apply-templates>
    </xsl:template>

    <xsl:template match="item" mode="blog.comments.list">
        <xsl:param name="postGuid"/>
        <xsl:param name="parents"/>
        <div class="com">
            <div class="row">
                <xsl:choose>
                    <xsl:when test="$parents = 1">
                        <div class="col-md-{$parents} dotted">
                            ••
                        </div>
                    </xsl:when>
                    <xsl:when test="$parents > 1">
                        <div class="col-md-{$parents} dotted">
                            •••
                        </div>
                    </xsl:when>
                </xsl:choose>
                <div class="col-md-1"></div>
                <div class="col-md-{11 - $parents}">
                    <div class="name">
                        <xsl:value-of select="@displayName"/>
                    </div>
                    <div class="date">
                        <xsl:call-template name="dateTime">
                            <xsl:with-param name="format">d F Y в H:i</xsl:with-param>
                            <xsl:with-param name="stringTime" select=".//property[@name='publishTime']/value/date"/>
                        </xsl:call-template>
                    </div>
                    <div class="text">
                        <xsl:value-of select=".//property[@name='contents']/value" disable-output-escaping="yes"/>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <xsl:apply-templates
                                    select="document(concat('widget://blog.comment.add.addForm?blogPost=', $postGuid, '&amp;blogComment=', @guid))"
                                    mode="blog.comments.replyTo"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <xsl:apply-templates select="item" mode="blog.comments.list">
            <xsl:with-param name="postGuid" select="$postGuid"/>
            <xsl:with-param name="parents" select="$parents + 1"/>
        </xsl:apply-templates>
    </xsl:template>
    <!-- Список комментариев <Конец> -->


</xsl:stylesheet>