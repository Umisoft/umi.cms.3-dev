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

    <xsl:template match="form">
        <form>
            <xsl:copy-of select="attributes/@*"/>
            <xsl:apply-templates select="elements/item" mode="formElement"/>
        </form>
    </xsl:template>

    <xsl:template match="item[tag='input']" mode="formElement">
        <div>
            <xsl:attribute name="class">
                <xsl:text>form-group </xsl:text>
                <xsl:if test="valid = 0">
                    <xsl:text>has-error</xsl:text>
                </xsl:if>
            </xsl:attribute>
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                </xsl:attribute>
                <xsl:value-of select="label"/>
            </label>
            <xsl:apply-templates select="errors" mode="formErrors"/>
            <input class="form-control">
                <xsl:copy-of select="attributes/@*"/>
            </input>
        </div>
    </xsl:template>

    <xsl:template match="item[tag='button']" mode="formElement">
        <button class="btn btn-primary">
            <xsl:copy-of select="attributes/@*"/>
            <xsl:value-of select="label"/>
        </button>
    </xsl:template>

    <xsl:template match="item[type='fieldset']" mode="formElement">
        <xsl:apply-templates select="elements/item" mode="formElement"/>
    </xsl:template>

    <xsl:template match="item[type='textarea' or type='wysiwyg']" mode="formElement">

        <div class="form-group">
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                </xsl:attribute>
                <xsl:value-of select="label"/>
            </label>
            <xsl:apply-templates select="errors" mode="formErrors"/>
            <textarea class="form-control" rows="4">
                <xsl:copy-of select="attributes/@*"/>
                <xsl:value-of select="value" disable-output-escaping="yes"/>
            </textarea>
        </div>
    </xsl:template>

    <xsl:template match="item[type='select' or type='multiSelect']" mode="formElement">
        <div class="form-group">
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                </xsl:attribute>
                <xsl:value-of select="label"/>
            </label>
            <xsl:apply-templates select="errors" mode="formErrors"/>
            <select class="form-control">
                <xsl:copy-of select="attributes/@*"/>
                <xsl:apply-templates select="choices/item" mode="selectOption"/>
            </select>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="selectOption">
        <option>
            <xsl:copy-of select="attributes/@*"/>
            <xsl:value-of select="label"/>
        </option>
    </xsl:template>

    <xsl:template match="item[type='checkboxGroup']" mode="formElement">
        <xsl:apply-templates select="errors" mode="formErrors"/>
        <xsl:apply-templates select="choices/item" mode="checkboxOption"/>
    </xsl:template>

    <xsl:template match="item" mode="checkboxOption">
        <label>
            <input>
                <xsl:copy-of select="attributes/@*"/>
                <xsl:value-of select="label"/>
            </input>
        </label>
    </xsl:template>

    <xsl:template match="item[type='checkbox']" mode="formElement">
        <input type="hidden" value="0" name="{attributes/@name}"/>
        <label>
            <xsl:attribute name="for">
                <xsl:value-of select="attributes/@id"/>
            </xsl:attribute>
            <input>
                <xsl:copy-of select="attributes/@*"/>
                <xsl:value-of select="label"/>
            </input>
        </label>
    </xsl:template>

    <xsl:template match="item[type='passwordWithConfirmation']" mode="formElement">

        <div>
            <xsl:attribute name="class">
                <xsl:text>form-group </xsl:text>
                <xsl:if test="valid = 0">
                    <xsl:text>has-error</xsl:text>
                </xsl:if>
            </xsl:attribute>
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                </xsl:attribute>
                <xsl:value-of select="label"/>
            </label>
            <xsl:apply-templates select="errors" mode="formErrors"/>
            <input class="form-control">
                <xsl:copy-of select="attributes/@*"/>
            </input>
        </div>

        <div class="form-group">
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                    <xsl:text>0</xsl:text>
                </xsl:attribute>
                <xsl:value-of select="document('translate://project.site.users/PasswordConfirmation')/result"/>
            </label>
            <input class="form-control">
                <xsl:copy-of select="attributes/@*"/>
                <xsl:attribute name="id">
                    <xsl:value-of select="attributes/@id"/>0
                </xsl:attribute>
            </input>
        </div>

    </xsl:template>

    <xsl:template match="item[type='captcha']" mode="formElement">

        <div>
            <xsl:attribute name="class">
                <xsl:text>form-group </xsl:text>
                <xsl:if test="valid = 0">
                    <xsl:text>has-error</xsl:text>
                </xsl:if>
            </xsl:attribute>
            <label>
                <xsl:attribute name="for">
                    <xsl:value-of select="attributes/@id"/>
                </xsl:attribute>
                <xsl:value-of select="label"/>
            </label>
            <xsl:apply-templates select="errors" mode="formErrors"/>

            <script type="text/javascript">
                function reloadCaptcha_<xsl:value-of select="sessionKey"/>() {
                    var img = document.getElementById('<xsl:value-of select="sessionKey"/>');
                    img.src = '<xsl:value-of select="url"/>?t=' + (new Date()).getTime();
                }
            </script>
            <div class="input-group">
                <span class="input-group-addon">
                    <img id="{sessionKey}" src="{url}" alt="{label}" title="{label}"/>
                    <button class="btn btn-default glyphicon glyphicon-refresh" onclick="reloadCaptcha_{sessionKey}();return false;"></button>
                </span>
                <input type="text" class="form-control input-lg">
                    <xsl:copy-of select="attributes/@*"/>
                </input>
            </div>

        </div>

    </xsl:template>

    <xsl:template match="item[type='hidden' or type='csrf']" mode="formElement">
        <div>
            <xsl:attribute name="class">
                <xsl:text>form-group </xsl:text>
                <xsl:if test="valid = 0">
                    <xsl:text>has-error</xsl:text>
                </xsl:if>
            </xsl:attribute>
            <xsl:apply-templates select="errors" mode="formErrors"/>
            <input class="form-control">
                <xsl:copy-of select="attributes/@*"/>
            </input>
        </div>
    </xsl:template>

    <xsl:template match="errors[item]" mode="formErrors">
        <div class="alert alert-danger">
            <ul>
                <xsl:apply-templates select="item" mode="formErrors"/>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="item" mode="formErrors">
        <li><xsl:value-of select="."/></li>
    </xsl:template>

</xsl:stylesheet>