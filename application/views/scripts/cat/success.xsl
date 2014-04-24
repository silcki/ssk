<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<!-- Catalog -->
	<xsl:template match="faq">
		<div class="block">
			<div class="def tr"></div>
			<div class="def tl"></div>
			<div class="blockcontent">
				<div class="head">
					<h2><a href="#"><xsl:value-of select="question"  disable-output-escaping="yes"/></a></h2>
				</div>
				<div class="text">
					<xsl:apply-templates select="answer" />
				</div>
			</div>
			<div class="def br"></div>
			<div class="def bl"></div>
		</div>
	</xsl:template>
	<!-- Catalog -->

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/></h1>		
	</div>
	<div class="text" style="clear: both;">
		<xsl:apply-templates select="docinfo/txt" />
	</div>
	
</xsl:template>

</xsl:stylesheet>