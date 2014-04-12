<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

<!-- Opros -->
	<xsl:template match="otvets">
		<div class="opdiv">
			<xsl:choose>
				<xsl:when test="position()=1">
					<input type="radio" name="opr" id="opr{@id}" value="{@id}" checked="checked"/>
				</xsl:when>
				<xsl:otherwise>
					<input type="radio" name="opr" id="opr{@id}" value="{@id}"/>
				</xsl:otherwise>
			</xsl:choose>
			<label for="opr{@id}">
				<xsl:value-of select="name"/>
			</label>
		</div>
	</xsl:template>

	<xsl:template match="vopros">
		<p class="head"><xsl:value-of select="name"/></p>
		<form action="/index/clientvote/client/{/page/data/client}/" method="post" id="vote">
			<div class="opros">
				<xsl:apply-templates select="otvets"/>
				<div class="send2" >
					<input type="image" src="/i/send.png"/>
				</div>
			</div>
		</form>
	</xsl:template>
<!-- Opros -->

<xsl:template match="data">
	<ul class="breadcrumbs">
		<li><a href="{/page/lang_name}/"><xsl:value-of select="/page/page_main" /></a></li>
		<!--<li><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></li>-->
	</ul>
	<h1 class="breadcrumbsh"><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
	<xsl:choose>
		<xsl:when test="count(vopros) &gt; 0">
			<div class="block oprholder">
				<div class="def tl"></div>
				<div class="def tr"></div>
				<div class="blockcontent">
					<xsl:apply-templates select="vopros"/>					
				</div>
				<div class="def bl"></div>
				<div class="def br"></div>
			</div>
		</xsl:when>
		<xsl:otherwise><xsl:apply-templates select="/page/docinfo/txt" /></xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>