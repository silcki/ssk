<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>
	
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