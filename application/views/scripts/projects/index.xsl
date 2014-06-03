<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<!-- Catalog -->
	<xsl:template match="projects">
		<div class="news">
			<p class="date"><xsl:value-of select="date"/></p>
			<p class="zag"><a href="{url}"><xsl:value-of select="name"/></a></p>
			<div class="text">
				<xsl:if test="image1/@src!=''">
					<a href="{url}"><img src="/images/projects/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}" /></a>
				</xsl:if>				
				<p><a href="{url}"><xsl:value-of select="descript"/></a></p>
			</div>
		</div>
		<xsl:if test="position()!=last()"><div class="line_devider">&#160;</div></xsl:if>		
	</xsl:template>
	<!-- Catalog -->
	
<xsl:template name="section_url">
	<xsl:choose>
		<xsl:when test="//data/@projects_id &gt; 0"><xsl:apply-templates select="/page/lang_name"/>/projects/all/n/<xsl:apply-templates select="/page/data/@projects_id"/>/</xsl:when>
		<xsl:otherwise><xsl:apply-templates select="/page/lang_name"/><xsl:value-of select="//data/@file_name"/></xsl:otherwise>
	</xsl:choose>		
</xsl:template>
	
<xsl:template name="section_first_url">
<xsl:value-of select="//data/@file_name"/>
</xsl:template>	

<xsl:template match="data">
	<div class="forprint">
		<h1><xsl:value-of select="docinfo/name"  disable-output-escaping="yes"/></h1>
	</div>
	<div class="text">
		<xsl:apply-templates select="docinfo/txt"/>
	</div>
	<ul class="catnews">
		<xsl:if test="@news_id &gt; 0"><li><a href="{/page/lang_name}/projects/"><xsl:value-of select="/page/all_projects" /></a></li></xsl:if>
	</ul>
	<div class="newses">
		<xsl:apply-templates select="projects" />					
	</div>
	<ul class="paging">
		<xsl:apply-templates select="section"/>
	</ul>
</xsl:template>

</xsl:stylesheet>