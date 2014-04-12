<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="projects_single/name"  disable-output-escaping="yes"/></h1>
		<p id="print"><a title="{/page/print_text}" href="#" onclick="window.print();"><xsl:value-of select="/page/print_text"/></a></p>
	</div>
	<div class="text">
		<xsl:apply-templates select="projects_single/txt" />
	</div>
</xsl:template>

</xsl:stylesheet>