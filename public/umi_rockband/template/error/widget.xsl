<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Этот шаблон обрабатывает ошибки в виджетах.
        Сообщение об ошибке будет видно в исходном коде страницы, в комментарии -->

    <xsl:template match="result[@widget='error']">
        <xsl:comment><xsl:value-of select="error/@message" /></xsl:comment>

        <!-- Раскомментировать для подробного отображения ошибки -->
        <!--div>
            <h3>Произошла ошибка при вызове виджета</h3>
            <p><xsl:value-of select="error/@message" /></p>
            <textarea><xsl:copy-of select="error/trace" /></textarea>
        </div-->
    </xsl:template>


</xsl:stylesheet>