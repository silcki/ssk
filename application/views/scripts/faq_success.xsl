<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

	<xsl:template match="an_path">		
		<xsl:choose>
			<xsl:when test="position()!=last()"><li><a href="{url}"><xsl:value-of select="name"/></a></li></xsl:when>
			<!--<xsl:otherwise><xsl:value-of select="name"/></xsl:otherwise>-->
		</xsl:choose>			
	</xsl:template>
	
<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/></h1>
	</div>
	<div class="text"><xsl:apply-templates select="/page/docinfo/txt" /></div>
</xsl:template>	

</xsl:stylesheet>