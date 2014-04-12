<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

	<xsl:template match="announcement">
		<div class="newsall">
			<div class="text">
				<p><xsl:apply-templates select="text" /></p>
				<dl>
					<xsl:if test="organization!=''">
						<dt><xsl:value-of select="/page/form_organization" />:</dt>
						<dd><xsl:value-of select="organization" /></dd>
					</xsl:if>
					<xsl:if test="country!=''">
						<dt><xsl:value-of select="/page/form_country" />:</dt>
						<dd><xsl:value-of select="country" /></dd>
					</xsl:if>
					<xsl:if test="city!=''">
						<dt><xsl:value-of select="/page/form_city" />:</dt>
						<dd><xsl:value-of select="city" /></dd>
					</xsl:if>
					<xsl:if test="name!=''">
						<dt><xsl:value-of select="/page/form_name" />:</dt>
						<dd><xsl:value-of select="name" /></dd>
					</xsl:if>
					<xsl:if test="phone!=''">
						<dt><xsl:value-of select="/page/form_phone" />:</dt>
						<dd><xsl:value-of select="phone" /></dd>
					</xsl:if>
					<xsl:if test="fax!=''">
						<dt><xsl:value-of select="/page/form_fax" />:</dt>
						<dd><xsl:value-of select="fax" /></dd>
					</xsl:if>
					<xsl:if test="email!=''">
						<dt><xsl:value-of select="/page/form_email" />:</dt>
						<dd><a href="mailto:{email}"><xsl:value-of select="email" /></a></dd>
					</xsl:if>							
					<dt><xsl:value-of select="/page/announcement_type" />:</dt>
					<dd><a href="/announcement/all/tid/{@types_id}/"><xsl:value-of select="at_name" /></a> | <a href="/announcement/all/rid/{@rubrics_id}/"><xsl:value-of select="ar_name" /></a></dd>
				</dl>
			</div>
		</div>
	</xsl:template>

<xsl:template match="data">		
	<ul class="breadcrumbs">
		<li><a href="{/page/lang_name}/"><xsl:value-of select="/page/page_main" /></a></li>
		<li><a href="{/page/docinfo/url}"><xsl:value-of select="/page/docinfo/name" /></a></li>
		<!--<li><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></li>-->
	</ul>
	<h1 class="breadcrumbsh"><xsl:value-of select="announcement/title"  disable-output-escaping="yes"/></h1>
	<div class="text">
		<xsl:apply-templates select="announcement" />
	</div>
</xsl:template>

</xsl:stylesheet>