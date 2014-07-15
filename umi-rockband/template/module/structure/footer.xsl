<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <xsl:template name="footer">
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <!-- Меню в футере <Начало>-->
                    <xsl:apply-templates select="document('widget://blog.category.list')" mode="footer.blog.root" />
                    <xsl:apply-templates select="document('widget://news.rubric.list')" mode="footer.news.root" />
                    <xsl:apply-templates select="document('widget://structure.menu.auto?depth=2')" mode="footer.content.root"/>
                    <!-- Меню в футере <Конец>-->
                    <div class="col-md-12 text-center">
                        <!-- Значки социальных сетей -->
                        <xsl:call-template name="footerSocial"/>
                        <!-- Текст копирайта -->
                        <p class="copy">
                            <xsl:apply-templates select="document('widget://structure.infoblock.view?infoBlock=commonInfoBlock')" mode="copyright"/>
                        </p>
                        <!-- Стрелка "Подняться вверх" -->
                        <a href="#top" class="top-scroll"><img src="/resources/umi-rockband/images/top-scroll.png" alt=""/></a>
                    </div>
                </div>
            </div>
        </footer>
    </xsl:template>


</xsl:stylesheet>