<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

	<!-- Catalog -->
	<xsl:template match="catalogue" mode="sub">
		<li><a href="{url}"><xsl:value-of select="name"/></a></li>
	</xsl:template>

	<xsl:template match="cattree" mode="catview">
		<xsl:param name="ccount"/>
		<xsl:variable name="pos" select="$ccount + position() + 1"/>
		<li>
			<xsl:if test="$pos mod 3=0">
				<xsl:attribute name="class">midle</xsl:attribute>
			</xsl:if>
			<a href="{url}">
				<xsl:if test="image1/@src!=''">
					<img src="/images/cat/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}"/>
				</xsl:if>
				<span><xsl:value-of select="name" disable-output-escaping="yes"/></span>
			</a>
		</li>
	</xsl:template>
	
	<xsl:template match="items">	
		<li>
			<xsl:if test="(position()-2) mod 3=0">
				<xsl:attribute name="class">midle</xsl:attribute>
			</xsl:if>
			<a href="{url}">
				<xsl:if test="image/@src!=''">
					<img src="/images/it/{image/@src}" alt="{name}" width="{image/@w}" height="{image/@h}"/>
				</xsl:if>
				<span><xsl:value-of select="name" disable-output-escaping="yes"/></span>
			</a>
			<!--<xsl:if test="count(catalogue[@parent_id=$cid]) &gt; 0">
				<ul>
					<xsl:apply-templates select="catalogue[@parent_id=$cid]" mode="sub"/>
				</ul>
			</xsl:if>-->
			<xsl:if test="description!=''">
				<p><xsl:value-of select="description" disable-output-escaping="yes"/></p>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Catalog -->
	
<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="//catinfo/name" disable-output-escaping="yes"/></h1>
	</div>
	<ul class="catalog">
		<xsl:variable name="pid" select="@cat_id"/>		
		<xsl:apply-templates select="itemnode/items"/>		
		<xsl:apply-templates select="//cattree[@parent_id=$pid]" mode="catview">
			<xsl:with-param name="ccount" select="count(itemnode/items)"/>
		</xsl:apply-templates>
	</ul>
	
	<xsl:if test="itemnode/txt!=''">
		<div class="text" style="clear: both;">
			<xsl:apply-templates select="itemnode/txt" />
		</div>
	</xsl:if>
	
</xsl:template>	

</xsl:stylesheet>