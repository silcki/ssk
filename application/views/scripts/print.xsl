<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_base.xsl"/>

	<xsl:template match="faq">
		<div class="block">
			<div class="def tr"></div>
			<div class="def tl"></div>
			<div class="blockcontent">
				<div class="head">
					<h2><a><xsl:value-of select="question"  disable-output-escaping="yes"/></a></h2>
				</div>
				<div class="text">
					<xsl:apply-templates select="answer" />
				</div>
			</div>
			<div class="def br"></div>
			<div class="def bl"></div>
		</div>
	</xsl:template>
		
	<xsl:template match="//data/item/elements">
		<li id="area{@name_num}"> <span><xsl:value-of select="@name_num" /></span> <img src="/images/item_elem/{image1/@src}" alt="{name}" />
			<p><strong><xsl:value-of select="name" /></strong>&#160;—&#160;<xsl:value-of select="description" /></p>
			<a href="back" class="back" title="back to image">back</a>
		</li>
	</xsl:template>
	
	<xsl:template match="error_messages">
		<li><xsl:value-of select="err_mess"/></li>
	</xsl:template>

<xsl:template name="title">
		<xsl:choose>
			<xsl:when test="//docinfo/title!=''">
				<xsl:apply-templates select="//docinfo/title"/>
			</xsl:when>
			<xsl:otherwise>СКЛАД СЕРВИС</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="banner_header_address">
		<xsl:apply-templates select="description"/>
	</xsl:template>
	
	<xsl:template name="item_template">
		<xsl:if test="/page/data/item">
			<h1 class="breadcrumbsh"><xsl:value-of select="//data/item/name" /></h1>
			<div class="card">
				<xsl:value-of select="//data/item/pop_image_text" disable-output-escaping="yes" />
				<div class="img">
					<img src="/images/it/{//data/item/image1/@src}" width="{//data/item/image1/@w}" height="{//data/item/image1/@h}" alt="" border="0" usemap="#Map" />
					<xsl:value-of select="//data/item/code_map_area" disable-output-escaping="yes" />
					<xsl:if test="//data/item/image2/@src!=''">
						<xsl:variable name="left" select="//data/item/image1/@w - 10"/>
						<xsl:variable name="top" select="//data/item/image1/@h - 10"/>					
					</xsl:if>
				</div>
				<xsl:apply-templates select="//data/item/under_image_text/txt" />
				<xsl:if test="count(//data/item/elements) &gt; 0">
					<h3><xsl:value-of select="/page/item_text" /></h3>			
					<ul class="stelazhmore">
						<xsl:apply-templates select="//data/item/elements" />
					</ul>
				</xsl:if>
				<xsl:apply-templates select="//data/item/txt" />				
			</div>
		</xsl:if>
	</xsl:template>
	
	<xsl:template name="doc_template">
	<xsl:if test="/page/data/docinfo/txt != ''">
		<h1 class="breadcrumbsh"><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
		<div class="newsitem">
			<div class="text">
				<xsl:apply-templates select="/page/data/docinfo/txt" />
			</div>
		</div>
	</xsl:if>
	</xsl:template>
	
	<xsl:template name="articles_template">
	<xsl:if test="//article_single != ''">
		<h1 class="breadcrumbsh"><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
		<div class="text">
			<xsl:apply-templates select="/page/data/article_single/txt" />
		</div>
	</xsl:if>
	</xsl:template>
	
	<xsl:template name="faq_template">
	<xsl:if test="//faq">
		<xsl:apply-templates select="//faq"/>
	</xsl:if>
	</xsl:template>

<xsl:template match="/page">
		<xsl:variable name="doctype"><![CDATA[<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">]]></xsl:variable>
		<!--<xsl:value-of select="$doctype" disable-output-escaping="yes"/>-->
		<html>
			<head>
				<title>
					<xsl:call-template name="title"/>
				</title>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>		
				<xsl:variable name="style"><![CDATA[<!--[if IE]><link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen"/><![endif]-->]]></xsl:variable>
				<link rel="stylesheet" type="text/css" href="/css/colors.css" media="screen"/>
				<link rel="stylesheet" type="text/css" href="/css/print.css" />
				<xsl:value-of select="$style" disable-output-escaping="yes"/>				
				<link id="favicon" href="/favicon.ico" rel="icon" type="image/x-icon" />
			</head>
			<body>
			
			<div id="header">
				<table width="100%" class="printhead">
					<tr>
						<td align="center" width="100%"><img src="/i/logo.png" width="147" height="57" /></td>
					</tr>
				</table>
			</div>

			<div id="content">				
				<xsl:call-template name="item_template"/>
				<xsl:call-template name="doc_template"/>
				<xsl:call-template name="articles_template"/>
				<xsl:call-template name="faq_template"/>
			</div>

</body>
</html>
</xsl:template>
</xsl:stylesheet>