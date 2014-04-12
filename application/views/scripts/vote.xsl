<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

<!-- News -->

<xsl:template match="news">
	<div class="newsitem">
		<p class="link"><a href="{url}"><xsl:value-of select="date"/></a></p>
		<p><xsl:value-of select="descript"  disable-output-escaping="yes"/></p>
	</div>	
</xsl:template>

<xsl:template match="news_groups" mode="news">
	<div id="tab{@id}" class="tabs">
		<xsl:apply-templates select="news" />
	</div>
</xsl:template>

<xsl:template match="news_groups">
	<xsl:choose>
		<xsl:when test="position()=1"><li class="active" id="tabs{@id}"><a href="#"><xsl:value-of select="name"/></a></li></xsl:when>
		<xsl:otherwise><li id="tabs{@id}"><a href="#"><xsl:value-of select="name"/></a></li></xsl:otherwise>
	</xsl:choose>	
</xsl:template>
<!-- News -->

<!-- Catalogue -->
<xsl:template match="cattree" mode="sub">
	<li><a href="{url}"><xsl:value-of select="name"/></a></li>
</xsl:template>

<xsl:template match="/page/cattree">
	<xsl:variable name="cid">
		<xsl:value-of select="@catalogue_id"/>
	</xsl:variable>
	<div class="rubrh">
		<div class="rubr">
			<div class="block {style}">
				<div class="def tl"></div>
				<div class="def tr"></div>
				<div class="blockcontent">
					<div class="bulet"><img src="/i/bulet1.png" alt="" /><img src="/i/bulet14.png" alt="" /></div>
					<div class="contenth">
						<div class="content">
							<h2>— <xsl:value-of select="name"/> -</h2>
							<xsl:if test="count(cattree[@parent_id=$cid]) &gt; 0">
								<ul>
									<xsl:apply-templates select="cattree[@parent_id=$cid]" mode="sub"/>
								</ul>
							</xsl:if>
						</div>
					</div>
				</div>
				<div class="def bl"></div>
				<div class="def br"></div>
			</div>
		</div>
	</div>
</xsl:template>
<!-- Catalogue -->

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
	<form action="/index/vote/" method="post" id="vote">
		<div class="opros">
			<xsl:apply-templates select="otvets"/>
			<div class="send2" ><input type="image" src="/i/send.png"/></div>
		</div>
	</form>
</xsl:template>
<!-- Opros -->


<xsl:template match="data">
		
		<xsl:apply-templates select="/page/cattree[@in_main=1]" />
		
		<div class="tabnews">
			<ul id="tabs">
				<xsl:apply-templates select="news_groups"/>
			</ul>
			<div class="block tabsholder">
				<div class="def tl"></div>
				<div class="def tr"></div>
				<div class="blockcontent">
					<xsl:apply-templates select="news_groups" mode="news"/>					
					<a href="/news/all/" class="allnews">все новости</a> </div>
				<div class="def bl"></div>
				<div class="def br"></div>
			</div>
		</div>
		<xsl:if test="count(vopros) &gt; 0">
			<div class="block oprholder">
				<div class="def tl"></div>
				<div class="def tr"></div>
				<div class="blockcontent">
					<xsl:apply-templates select="vopros"/>					
				</div>
				<div class="def bl"></div>
				<div class="def br"></div>
			</div>
		</xsl:if>
</xsl:template>

</xsl:stylesheet>