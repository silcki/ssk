<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

<xsl:template match="data">
	<ul class="breadcrumbs">
		<li><a href="{/page/lang_name}/"><xsl:value-of select="/page/page_main" /></a></li>
		<!--<li><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></li>-->
	</ul>
	<h1 class="breadcrumbsh"><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
	<div class="newsitem">
		<xsl:apply-templates select="docinfo/txt" />
	</div>
</xsl:template>

</xsl:stylesheet>