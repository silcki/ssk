<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

<!-- Catalogue -->
<xsl:template match="cattree" mode="sub">
	<li>
		<a href="{url}"><xsl:value-of select="name"/></a>
		<xsl:if test="count(itemnode/items[@in_main=1]) &gt; 0">
			<ul>
				<xsl:apply-templates select="itemnode/items" mode="sub"/>
			</ul>					
		</xsl:if>
	</li>
</xsl:template>

<xsl:template match="items" mode="sub">
	<li><a href="{url}"><xsl:value-of select="name"/></a></li>
<!--	<li><a href="{url}"><xsl:value-of select="menu_name"/></a></li> -->
</xsl:template>

<xsl:template match="/page/cattree">
	<xsl:variable name="cid">
		<xsl:value-of select="@catalogue_id"/>
	</xsl:variable>
	<div class="rubr">
		<xsl:if test="position() mod 2 = 0">
			<xsl:attribute name="class">rubr rubr-right</xsl:attribute>
		</xsl:if>
		<div class="bulet"><a href="{url}"><img src="/images/cat/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}"/></a></div>
		<div class="content">
			<h2><a href="{url}"><xsl:value-of select="name"/></a></h2>
			<xsl:if test="count(cattree[@parent_id=$cid and @in_main=1]) &gt; 0">
				<ul>
					<xsl:apply-templates select="cattree[@parent_id=$cid and @in_main=1]" mode="sub"/>					
				</ul>
			</xsl:if>
			<xsl:if test="count(itemnode/items[@in_main=1]) &gt; 0">
				<ul>
					<xsl:apply-templates select="itemnode/items[@in_main=1]" mode="sub"/>
				</ul>					
			</xsl:if>
		</div>
	</div>
</xsl:template>

<xsl:template match="headers" mode="pos">
	<li><a href="#"><xsl:value-of select="position()"/></a></li>
</xsl:template>

<xsl:template match="headers">
	<li>
		<table>
			<tr>
				<td>
					<div class="gal_img">
						<xsl:choose>
							<xsl:when test="url !='' ">
								<a href="{url}"><img src="/images/header/{image/@src}" alt="" width="{image/@w}" height="{image/@h}" /></a>
							</xsl:when>
							<xsl:otherwise><img src="/images/header/{image/@src}" alt="" width="{image/@w}" height="{image/@h}" /></xsl:otherwise>
						</xsl:choose>
					</div>
				</td>
				<td class="text">
					<xsl:choose>
						<xsl:when test="image_alt_text/@src!=''">
						
							<xsl:choose>
								<xsl:when test="url !='' ">
									<a href="{url}"><img src="/images/header/{image_alt_text/@src}" alt="" width="{image_alt_text/@w}" height="{image_alt_text/@h}" /></a>
								</xsl:when>
								<xsl:otherwise><img src="/images/header/{image_alt_text/@src}" alt="" width="{image_alt_text/@w}" height="{image_alt_text/@h}" /></xsl:otherwise>
							</xsl:choose>
							
						</xsl:when>
						<xsl:otherwise><xsl:apply-templates select="description"/></xsl:otherwise>
					</xsl:choose>
				</td>
			</tr>
		</table>
	</li>
</xsl:template>

<xsl:template match="clients_li" mode="pos">
	<li><a href="#"><xsl:value-of select="position()"/></a></li>
</xsl:template>

<xsl:template match="clients">
	<td><img src="/images/cl/{image/@src}" alt="{name}" style="width:90px;"/></td>
</xsl:template>

<xsl:template match="clients_td">
	<tr>
		<xsl:apply-templates select="clients"/>
	</tr>
</xsl:template>

<xsl:template match="clients_li">
	<li>
		<table>
			<xsl:apply-templates select="clients_td"/>
		</table>
	</li>
</xsl:template>

<xsl:template match="//div[@class='clients']">
	<div class="col3">
		<p class="head"><xsl:value-of select="/page/text_our_clients"/></p>
		<div class="brands_hold">
			<ul id="brands">
				<xsl:apply-templates select="//clients_li"/>
			</ul>
			<ul class="number">
				<xsl:apply-templates select="//clients_li" mode="pos"/>							
			</ul>
		</div>
	</div>
</xsl:template>

<xsl:template match="news">
	<li>
		<a href="{url}"><xsl:value-of select="name"/></a>
	</li>
</xsl:template>

<xsl:template match="//div[@class='index_news']">
	<!--<div class="col3">-->
		<p class="head"><xsl:value-of select="/page/text_index_news"/></p>
		<ul id="index_news">
			<xsl:apply-templates select="//data/news"/>
		</ul>

		<xsl:apply-templates select="//index_under_news/description"/>

	<!--</div>-->
</xsl:template>

<xsl:template match="data">	
	<xsl:apply-templates select="docinfo/txt"/>	
</xsl:template>

</xsl:stylesheet>