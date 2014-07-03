<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:output
            encoding="utf-8"
            method="xml"
            indent="yes"
            cdata-section-elements="script noscript"
            omit-xml-declaration="yes"
          />

    <xsl:template match="/">
        <textarea>
            <xsl:copy-of select="." />
        </textarea>
    </xsl:template>
</xsl:stylesheet>