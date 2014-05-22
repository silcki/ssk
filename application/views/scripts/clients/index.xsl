<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "../symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

    <xsl:template match="client_country|client_scope|client_product_type">
        <option value="{@id}">
            <xsl:if test="@active = 1">
                <xsl:attribute name="selected">selected</xsl:attribute>
            </xsl:if>
            <xsl:value-of select="name"/>
        </option>
    </xsl:template>

	<xsl:template match="clients">
		<td><img src="/images/cl/{image1/@src}" alt="{name}" height="{image1/@h}" width="{image1/@w}"/></td>
	</xsl:template>

	<xsl:template match="clients_tr">
		<tr>
			<xsl:apply-templates select="clients"/>
		</tr>
	</xsl:template>

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="docinfo/name" disable-output-escaping="yes"/></h1>
	</div>

    <div class="client_filter clear">
        <div class="loadingForm">
            <form action="" data-file-name="{@file_name}" method="post">
                <fieldset>
                    <xsl:if test="count(client_country) &gt; 0">
                        <div class="block">
                            <label>Страны:</label>
                            <select name="client_country" class="client_country">
                                <option value="0">---укажите страну--</option>
                                <xsl:apply-templates select="client_country"/>
                            </select>
                        </div>
                    </xsl:if>

                    <xsl:if test="count(client_scope) &gt; 0">
                        <div class="block">
                            <label>Сфера деятельности:</label>
                            <select name="client_scope" class="client_scope">
                                <option value="0">---укажите сферу деятельности--</option>
                                <xsl:apply-templates select="client_scope"/>
                            </select>
                        </div>
                    </xsl:if>

                    <xsl:if test="count(client_product_type) &gt; 0">
                        <div class="block">
                            <label>Тип продукции:</label>
                            <select name="client_product_type" class="client_product_type">
                                <option value="0">---укажите тип продукции--</option>
                                <xsl:apply-templates select="client_product_type"/>
                            </select>
                        </div>
                    </xsl:if>
                    <div class="clear" />
                    <div class="row">
                        <a href="#" id="client_filter_send" class="btn_send">Отправить</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

	<table class="clients">
		<xsl:apply-templates select="clients_tr"/>
	</table>
</xsl:template>

</xsl:stylesheet>