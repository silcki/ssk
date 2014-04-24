<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<!-- Catalog -->
	<xsl:template match="articles">
		<div class="news">
			<p class="date"><xsl:value-of select="date"/></p>
			<p class="zag"><a href="{url}"><xsl:value-of select="name"/></a></p>
			<div class="text">
				<xsl:if test="image1/@src!=''">
					<a href="{url}"><img src="/images/article/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}" /></a>
				</xsl:if>
				<p><a href="{url}"><xsl:value-of select="descript"/></a></p>
			</div>
		</div>
		<xsl:if test="position()!=last()"><div class="line_devider">&#160;</div></xsl:if>
	</xsl:template>
	
	<xsl:template match="article_group">
		<xsl:choose>
			<xsl:when test="@on_path=1"><li><span><xsl:value-of select="name" /></span></li></xsl:when>
			<xsl:otherwise><li><a href="{url}"><xsl:value-of select="name" /></a></li></xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<!-- Catalog -->
	
<xsl:template name="section_url">
	<xsl:choose>
		<xsl:when test="//data/@article_id &gt; 0"><xsl:apply-templates select="/page/lang_name"/>/articles/all/n/<xsl:apply-templates select="/page/data/@article_id"/>/</xsl:when>
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
		<xsl:if test="@article_id &gt; 0"><li><a href="{/page/lang_name}/articles/"><xsl:value-of select="/page/all_articles" /></a></li></xsl:if>
		<xsl:apply-templates select="article_group" />
	</ul>
	<div class="newses">
		<xsl:apply-templates select="articles" />					
	</div>
	<ul class="paging">
		<xsl:apply-templates select="/page/data/section"/>
	</ul>
</xsl:template>

</xsl:stylesheet>