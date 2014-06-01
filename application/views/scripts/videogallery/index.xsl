<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<!-- Gallery -->
	<xsl:template match="gallery_group">
		<li> 
			<xsl:if test="(position()-2) mod 3=0">
				<xsl:attribute name="class">midle</xsl:attribute>
			</xsl:if>
			<a href="{url}"><img src="/images/gallery_video/{image1/@src}" alt="{name}" height="{image1/@h}"/><span><xsl:value-of select="name" disable-output-escaping="yes"/></span></a> </li>
		<!--<p><xsl:value-of select="description" disable-output-escaping="yes"/></p>-->
	</xsl:template>
	<!-- Gallery -->

<xsl:template match="data">
<!--	<div class="breadcrumbs">
		<div class="holder">
			<ul>
				<li><a href="{/page/lang_name}/"><xsl:value-of select="/page/page_main" /></a></li>
				<xsl:if test="count(breadcrumbs) &gt; 0">
					<li><a href="{/page/lang_name}/gallery/"><xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/></a></li>
					<xsl:apply-templates select="breadcrumbs" />
				</xsl:if>
				<li>
					<xsl:choose>
						<xsl:when test="//page/data/docinfo/name!=''"><xsl:value-of select="//page/data/docinfo/name" disable-output-escaping="yes"/></xsl:when>
						<xsl:otherwise><xsl:value-of select="//page/docinfo/name" disable-output-escaping="yes"/></xsl:otherwise>
					</xsl:choose>
				</li>
			</ul>
		</div>
	</div>-->
	<div class="forprint">
		<h1><xsl:value-of select="docinfo/name" disable-output-escaping="yes"/></h1>
	</div>	
	<ul class="gallery">
		<xsl:apply-templates select="gallery_group" />
	</ul>
	<xsl:if test="txt!=''">
		<div class="text" style="clear:both;"><xsl:apply-templates select="txt" /></div>
	</xsl:if>

</xsl:template>

</xsl:stylesheet>