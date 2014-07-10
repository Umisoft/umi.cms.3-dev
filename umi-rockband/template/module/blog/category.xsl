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


    <xsl:template match="result[@widget = 'blog.category.list']//item" mode="footer.blog.root">
        <div class="col-md-2">
            <h5><xsl:value-of select="@displayName" /></h5>
            <ul class="menu">
                <xsl:apply-templates select="document(concat('widget://blog.category.list?parentCategory=', @guid))" mode="footer.blog.categories" />
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.category.list']//item" mode="footer.blog.categories">
        <li><a href="{@url}"><xsl:value-of select="@displayName" /></a></li>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.category.list']" mode="rss">
        <textarea>
            <xsl:copy-of select="." />
        </textarea>
        <xsl:apply-templates select="list/collection/item" mode="rss"/>
    </xsl:template>

    <xsl:template match="item" mode="rss">
        <xsl:apply-templates select="document(concat('widget://blog.category.rssLink', @guid))" mode="rss"/>
    </xsl:template>

    <xsl:template match="result[@widget = 'blog.category.rssLink']" mode="rss">
        <textarea>
            <xsl:copy-of select="." />
        </textarea>
    </xsl:template>


</xsl:stylesheet>