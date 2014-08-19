<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns:umi="http://umi-cms.ru/xsl"
                exclude-result-prefixes="php umi">

    <!-- Форма на странице модуля Поиск <Начало> -->
    <xsl:template match="form" mode="search.form">
        <div class="row">
            <form class="col-sm-12 col-md-offset-1 col-md-10 form-horizontal">
                <xsl:apply-templates select="attributes" mode="all.form"/>
                <div class="input-group">
                    <xsl:apply-templates select="elements/item" mode="search.form"/>
                </div>
            </form>
        </div>
    </xsl:template>

    <xsl:template match="item[type='text']" mode="search.form">
        <input class="form-control">
            <xsl:apply-templates select="attributes" mode="all.form"/>
        </input>
    </xsl:template>

    <xsl:template match="item[type='submit']" mode="search.form">
        <span class="input-group-btn">
            <xsl:element name="{tag}">
                <xsl:attribute name="class">btn btn-primary</xsl:attribute>
                <xsl:apply-templates select="attributes/@value" mode="all.form"/>
                <xsl:apply-templates select="attributes/@type" mode="all.form"/>
                <xsl:value-of select="label"/>
            </xsl:element>
        </span>
    </xsl:template>
    <!-- Форма на странице модуля Поиск <Конец> -->

    <xsl:template match="form" mode="default.form">
        <xsl:param name="css.class"/>
        <div class="row">
            <form>
                <xsl:attribute name="class">
                    <xsl:choose>
                        <xsl:when test="$css.class">
                            <xsl:value-of select="$css.class"/>
                        </xsl:when>
                        <xsl:otherwise>
                            col-sm-12 col-md-6
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:attribute>
                <xsl:apply-templates select="attributes" mode="all.form"/>
                <xsl:apply-templates select="elements/item[attributes/@type = 'hidden']" mode="default.form"/>
                <xsl:apply-templates select="elements/item[not(attributes/@type='hidden')]" mode="default.form"/>
            </form>
        </div>
    </xsl:template>

    <xsl:template match="form//attributes" mode="all.form">
        <xsl:apply-templates select="@*" mode="all.form"/>
    </xsl:template>

    <xsl:template match="@*" mode="all.form">
        <xsl:attribute name="{name(.)}">
            <xsl:value-of select="."/>
        </xsl:attribute>
    </xsl:template>

    <xsl:template match="item" mode="default.form">
        <div class="form-group">
            <strong>Unknown type for the form element "<xsl:value-of select="type"/>"</strong>
        </div>
    </xsl:template>

    <xsl:template match="item[type='textarea']" mode="default.form">
        <div class="form-group">
            <xsl:apply-templates select="." mode="default.form.validation" />
            <label class="control-label">
                <xsl:value-of select="label"/>:
                <xsl:apply-templates select="errors" mode="default.form" />
                <textarea class="form-control" rows="4">
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                </textarea>
            </label>
        </div>
    </xsl:template>

    <xsl:template match="item[type='text' or type='password']" mode="default.form">
        <div class="form-group">
            <xsl:apply-templates select="." mode="default.form.validation" />
            <label class="control-label">
                <xsl:value-of select="label"/>:
                <xsl:apply-templates select="errors" mode="default.form" />
                <input class="form-control">
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                </input>
            </label>
        </div>
    </xsl:template>

    <xsl:template match="item[type='passwordWithConfirmation']" mode="default.form">
        <div class="form-group">
            <xsl:apply-templates select="." mode="default.form.validation" />
            <label class="control-label">
                <xsl:value-of select="label"/>:
                <xsl:apply-templates select="errors" mode="default.form" />
                <input class="form-control">
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                </input>
            </label>
        </div>
        <div class="form-group">
            <xsl:apply-templates select="." mode="default.form.validation" />
            <label class="control-label">
                <xsl:value-of select="document('translate://project.site.users/Confirm%20password')/result"/>
                <input class="form-control">
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                </input>
            </label>
        </div>
    </xsl:template>

    <xsl:template match="item[type='captcha']" mode="default.form">
        <div class="form-group">
            <xsl:apply-templates select="." mode="default.form.validation" />
            <label> <xsl:value-of select="label" /> </label>
            <xsl:apply-templates select="errors" mode="default.form" />
            <script type="text/javascript">
                function reloadCaptcha_<xsl:value-of select="sessionKey" />() {
                var img = document.getElementById('<xsl:value-of select="sessionKey" />');
                img.src = '<xsl:value-of select="url" />?t=' + (new Date()).getTime();
                }
            </script>
            <div class="input-group">
                <span class="input-group-addon">
                    <img id="{sessionKey}" src="{url}" alt="{label}" title="{label}" />
                    <button class="btn btn-primary glyphicon glyphicon-refresh" onclick="reloadCaptcha_{sessionKey}();return false;"></button>
                </span>
                <input type="text" class="form-control input-lg">
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                </input>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item[type='submit']" mode="default.form">
        <div class="form-group">
            <div class="text-right">
                <xsl:element name="{tag}">
                    <xsl:attribute name="class">btn btn-primary</xsl:attribute>
                    <xsl:apply-templates select="attributes" mode="all.form"/>
                    <xsl:value-of select="label"/>
                </xsl:element>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="item[type='hidden' or type='csrf']" mode="default.form">
        <xsl:element name="{tag}">
            <xsl:apply-templates select="attributes" mode="all.form"/>
        </xsl:element>
    </xsl:template>

    <xsl:template match="item" mode="default.form.validation">
        <xsl:if test="valid = 0 or not(valid)">
            <xsl:attribute name="class">form-group has-error</xsl:attribute>
        </xsl:if>
    </xsl:template>

    <xsl:template match="errors" mode="default.form" />
    <xsl:template match="errors[item]" mode="default.form">
        <div class="alert alert-danger">
            <ul>
                <xsl:apply-templates select="item" mode="default.form" />
            </ul>
        </div>
    </xsl:template>
    <xsl:template match="errors/item" mode="default.form">
        <li><xsl:value-of select="." /></li>
    </xsl:template>

</xsl:stylesheet>