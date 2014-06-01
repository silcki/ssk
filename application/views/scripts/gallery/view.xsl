<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<!-- Gallery -->
	<xsl:template match="gallery">
		<li>
			<xsl:choose>
				<xsl:when test="(position()-2) mod 4=0">
					<xsl:attribute name="class">midle1</xsl:attribute>
				</xsl:when>
				<xsl:when test="(position()-3) mod 4=0">
					<xsl:attribute name="class">midle2</xsl:attribute>
				</xsl:when>
			</xsl:choose>
			<a href="/images/gallery/{image2/@src}" rel="gallery" title="{description}" class="fotofancybox" data-fancybox-group="button">
				<xsl:choose>
					<xsl:when test="description != ''">
						<xsl:attribute name="title"><xsl:value-of select="description"/></xsl:attribute>
					</xsl:when>
					<xsl:otherwise>
						<xsl:attribute name="title"><xsl:value-of select="name"/></xsl:attribute>
					</xsl:otherwise>
				</xsl:choose>
				<img src="/images/gallery/{image1/@src}" alt="{name}" height="{image1/@h}" width="{image1/@w}"/><!--<span><xsl:apply-templates select="description"/></span>--></a></li>
	</xsl:template>
	<!-- Gallery -->
<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="docinfo/name" disable-output-escaping="yes"/></h1>
	</div>
	<ul class="gallery2">
		<xsl:apply-templates select="gallery" />
	</ul>
	<xsl:if test="txt!=''">
		<div class="text" style="clear:both;"><p class="linktoitem"><xsl:apply-templates select="txt" /></p></div>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>