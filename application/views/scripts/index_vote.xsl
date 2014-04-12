<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="_main.xsl"/>

<xsl:template match="resultotvets">
	<li>
		<xsl:if test="@is_max=1">
			<xsl:attribute name="class">top</xsl:attribute>
		</xsl:if>
		<strong><xsl:value-of select="name" /></strong>
		<div class="diagram"> <span style="width: {@percent}%;" class="image">диаграма</span> <span class="text">(<xsl:value-of select="@count" /> / <xsl:value-of select="@percent" />%</span>)</div>
	</li>
</xsl:template>

<xsl:template match="resultvopros">
	<div class="box">
		<h2><xsl:value-of select="name" /></h2>
		<p>Сроки проведения опроса: <span class="date"><xsl:value-of select="data_start"/> - <xsl:value-of select="data_stop"/></span></p>
		<ul class="vote-list">
			<xsl:apply-templates select="resultotvets"/>			
		</ul>
		<p>Голосов: <span class="date"><xsl:value-of select="@count"/></span></p>
	</div>
</xsl:template>

<xsl:template match="data">
	<h1 class="breadcrumbsh"><xsl:value-of select="/page/arc_vote" /></h1>	
	<div class="vote">
		<!--<ul class="catnews">
			<li><a href="#">Текушие</a></li>
			<li><span>Архивные</span></li>
		</ul>-->
		<xsl:apply-templates select="resultvopros"/>
	</div>
</xsl:template>
</xsl:stylesheet>