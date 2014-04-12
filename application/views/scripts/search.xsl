<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

	<xsl:template match="search_result">
		<!--<li><p><a href="{href}"><xsl:value-of select="name" /></a></p></li>-->
		<li><p><a href="{href}"><xsl:apply-templates select="name" /></a></p></li>
	</xsl:template>

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
		<p id="print"><a title="{/page/print_text}" href="#" onclick="window.print();"><xsl:value-of select="/page/print_text"/></a></p>
	</div>
	<div class="newsall">
		<div class="block news">
			<div class="def tr"></div>
			<div class="def tl"></div>
			<div class="blockcontent">
				<!--<div class="head"><h2><xsl:value-of select="/page/search_catalog" /></h2></div>-->
				<xsl:if test="count(search_result) &gt; 0">
					<div class="text">
						<ul class="col">
							<xsl:apply-templates select="search_result" />
						</ul>
					</div>
				</xsl:if>
			</div>
			<div class="def br"></div>
			<div class="def bl"></div>
		</div>
	</div>			
</xsl:template>

</xsl:stylesheet>