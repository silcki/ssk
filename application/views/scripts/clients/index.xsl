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
    <xsl:apply-templates select="docinfo/txt"/>

    <div class="loadingForm">
        <form action="" data-file-name="{@file_name}" method="post">
            <fieldset class="client_filter">
                <xsl:if test="count(client_scope) &gt; 0">
                    <div class="block">
                        <label>Сфера деятельности:</label>
                        <select name="client_scope" class="client_scope">
                            <option value="0">Все сферы</option>
                            <xsl:apply-templates select="client_scope"/>
                        </select>
                    </div>
                </xsl:if>

                <xsl:if test="count(client_product_type) &gt; 0">
                    <div class="block">
                        <label>Тип продукции:</label>
                        <select name="client_product_type" class="client_product_type">
                            <option value="0">Все типы</option>
                            <xsl:apply-templates select="client_product_type"/>
                        </select>
                    </div>
                </xsl:if>

                <xsl:if test="count(client_country) &gt; 0">
                    <div class="block">
                        <label>Страны:</label>
                        <select name="client_country" class="client_country">
                            <option value="0">Все страны</option>
                            <xsl:apply-templates select="client_country"/>
                        </select>
                    </div>
                </xsl:if>

                <div class="block">
                    <label>Сортировать:</label>
                    <select name="client_sort" class="client_sort">
                        <option value="0">Без сортировки</option>
                        <option value="1">
                            <xsl:if test="@asc = 'asc'"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
                            По имени (А-Я)</option>
                        <option value="2">
                            <xsl:if test="@asc = 'desc'"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
                            По имени (Я-А)</option>
                    </select>
                </div>
                <div class="clear" />
            </fieldset>
            <div class="row">
                <a href="#" id="client_filter_send" class="btn_send">Применить</a>
            </div>
        </form>
    </div>

	<table class="clients">
		<xsl:apply-templates select="clients_tr"/>
	</table>
</xsl:template>

</xsl:stylesheet>