<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:output
        encoding="utf-8"
        method="html"
        indent="yes"
        cdata-section-elements="script noscript"
        omit-xml-declaration="yes"
        doctype-system="about:legacy-compat"
        />

    <!-- Языки в хедере <Начало> -->
    <xsl:template match="locales[locale]">
        <ul class="langs">
            <xsl:apply-templates select="locale" />
        </ul>
    </xsl:template>

    <xsl:template match="locale">
        <li><a href="{@url}">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[@current = '1']">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a>/</li>
    </xsl:template>

    <xsl:template match="locale[position() = last()]">
        <li><a href="{@url}">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a></li>
    </xsl:template>

    <xsl:template match="locale[@current = '1' and position() = last()]">
        <li><a href="{@url}" class="active">
            <xsl:value-of select="document(concat('translate://project.site/', @id))/result"/>
        </a></li>
    </xsl:template>
    <!-- Языки в хедере <Конец> -->

    <!-- Значки социальных сетей <Начало> -->
    <xsl:template name="footerSocial">
        <ul class="social">
            <li><a href="#"><img src="/resources/umi-rockband/images/social/facebook.png" alt="" /></a></li>
            <li><a href="#"><img src="/resources/umi-rockband/images/social/twitter.png" alt="" /></a></li>
            <li><a href="#"><img src="/resources/umi-rockband/images/social/google.png" alt="" /></a></li>
        </ul>
    </xsl:template>
    <!-- Значки социальных сетей <Конец> -->

    <!-- Шаблон по умолчанию для виджетов <Начало> -->
    <xsl:template match="result">
        <textarea id="debug">
            <xsl:copy-of select="." />
        </textarea>
    </xsl:template>
    <!-- Шаблон по умолчанию для виджетов <Конец> -->

    <!-- Шаблон для вывода сообщений об ошибках <Начало> -->
    <xsl:template match="contents[error]">
        <div class="content-main">
            <div class="container-fluid">
                <div class="block">
                    <h3>
                        Ошибка:
                        <span style="color:red;">
                            <xsl:value-of select="error/@message" />
                        </span>
                    </h3>
                    <ul>
                        <xsl:apply-templates select="stack/item" mode="errorStackItem"/>
                    </ul>
                </div>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="errorStackItem">
        <li>
            <xsl:value-of select="concat(position(), '.: ')" />
            <xsl:value-of select="@message" />
        </li>
    </xsl:template>
    
    <!-- Шаблон для вывода сообщений об ошибках <Конец> -->

</xsl:stylesheet>