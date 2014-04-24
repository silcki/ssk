<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<xsl:template match="/page/main_menu/main_children_menu">
		<li><a href="{url}"><xsl:value-of select="name" /></a></li>
	</xsl:template>

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/></h1>		
	</div>
	<div class="newsitem">	
		<div class="text">
			<xsl:apply-templates select="client_data/txt" />
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>