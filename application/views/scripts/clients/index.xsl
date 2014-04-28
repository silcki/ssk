<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "../symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

    <xsl:template match="client_country">
        <li>
            <input type="checkbox" id="country{@id}" value="{@id}" name="client_country[]"/>
            <label for="country{@id}"><xsl:value-of select="name"/></label>
        </li>
    </xsl:template>
    
    <xsl:template match="client_scope">
        <li>
            <input type="checkbox" id="client_scope{@id}" value="{@id}" name="client_scope[]"/>
            <label for="client_scope{@id}"><xsl:value-of select="name"/></label>
        </li>
    </xsl:template>
    
    <xsl:template match="client_product_type">
        <li>
            <input type="checkbox" id="client_product_type{@id}" value="{@id}" name="client_product_type[]"/>
            <label for="client_product_type{@id}"><xsl:value-of select="name"/></label>
        </li>
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
	<table class="clients">
		<xsl:apply-templates select="clients_tr"/>
	</table>
</xsl:template>

</xsl:stylesheet>