<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<xsl:template match="main_menu" mode="tree">
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="@is_new_win='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li>
			<a href="{url}" target="{$target}"><xsl:value-of select="name"/></a>
			<xsl:choose>
				<xsl:when test="spec_url = '/cat/all/' ">
					<ul>
						<xsl:apply-templates select="//page/cattree" mode="tree"/>
					</ul>
				</xsl:when>
				<xsl:when test="spec_url = '/gallery/' ">
					<ul>
						<xsl:apply-templates select="//data/gallery_group"/>
					</ul>
				</xsl:when>
				<xsl:when test="spec_url = '/videogallery/' ">
					<ul>
						<xsl:apply-templates select="//data/video_gallery_group"/>
					</ul>
				</xsl:when>
				<xsl:when test="spec_url = '/news/' ">
					<ul>
						<xsl:apply-templates select="//news"/>
					</ul>
				</xsl:when>
				<xsl:when test="spec_url = '/articles/' ">
					<ul>
						<xsl:apply-templates select="//articles"/>
					</ul>
				</xsl:when>
				<xsl:when test="count(main_menu) &gt; 0 ">
					<ul>
						<xsl:apply-templates select="main_menu" mode="tree"/>
					</ul>
				</xsl:when>
			</xsl:choose>
		</li>
	</xsl:template>
	<!-- Top menu -->
	
	<!-- Каталог-->
	<xsl:template match="item_item" mode="tree">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
		</li>
	</xsl:template>
	<!-- Каталог-->
	
	<!-- Каталог-->
	<xsl:template match="cattree" mode="tree">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
			<xsl:if test="count(cattree) &gt; 0">
				<ul>
					<xsl:apply-templates select="cattree" mode="tree"/>
				</ul>
			</xsl:if>
			<xsl:if test="count(item_item) &gt; 0">
				<ul>
					<xsl:apply-templates select="item_item" mode="tree"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Каталог-->
	
	<!-- Галерея-->
	<xsl:template match="gallery_group">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
			<xsl:if test="count(gallery_group) &gt; 0 and @level &lt; 2">
				<ul>
					<xsl:apply-templates select="gallery_group"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	
	<xsl:template match="video_gallery_group">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
			<xsl:if test="count(video_gallery_group) &gt; 0 and @level &lt; 2">
				<ul>
					<xsl:apply-templates select="video_gallery_group"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Галерея-->
	
	<!-- Новости-->
	<xsl:template match="news">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
		</li>
	</xsl:template>
	
	<xsl:template match="news_group">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
			<xsl:if test="count(news) &gt; 0">
				<ul>
					<xsl:apply-templates select="news"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Новости-->
	
	<!-- Статьи-->
	<xsl:template match="articles">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
		</li>
	</xsl:template>
	
	<xsl:template match="article_group">
		<li>
			<a href="{url}"><xsl:value-of select="name"/></a>
			<xsl:if test="count(articles) &gt; 0">
				<ul>
					<xsl:apply-templates select="articles"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Статьи-->


<xsl:template match="data">
	<h1><xsl:value-of select="//docinfo/name" disable-output-escaping="yes"/></h1>
	<xsl:if test="count(/page/main_menu) &gt; 0">
		<ul class="map">		
			<xsl:apply-templates select="/page/main_menu" mode="tree"/>
		</ul>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>