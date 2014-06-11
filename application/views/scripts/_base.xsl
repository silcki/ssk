<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="counter.xsl"/>
<xsl:decimal-format name="european" decimal-separator="," grouping-separator=" " />
  <!--  Для вывода чистого XHTML использовать это декларацию! -->
<!--<xsl:output encoding="UTF-8" indent="yes" omit-xml-declaration="yes" method="xml"/>--> 
<!--<xsl:output encoding="UTF-8" indent="yes" omit-xml-declaration="yes" method="html"/>-->

<xsl:output encoding="UTF-8" indent="yes" omit-xml-declaration="yes" method="xml"
doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<!-- Simple tags -->
<xsl:template match="h1|h2|h3|h4|h5|b|i|u|sub|sup|nobr|span|div|small|strong|em|li|dl|ul|ol|img|link|a">
    <xsl:element name="{local-name()}">
        <xsl:copy-of select="@*"/>        
        <xsl:apply-templates />
    </xsl:element>
</xsl:template>

<xsl:template match="text()"><xsl:value-of select="."/></xsl:template>
<xsl:template match="nbsp"><xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text></xsl:template>
<xsl:template match="amp"><xsl:text disable-output-escaping="yes">&amp;amp;</xsl:text></xsl:template>
<xsl:template match="symbol"><xsl:text disable-output-escaping="yes">&amp;</xsl:text><xsl:value-of select="@value"/>;</xsl:template>
<xsl:template match="br"><br/></xsl:template>
<xsl:template match="wbr"><wbr/></xsl:template>
<xsl:template match="b"><b><xsl:apply-templates/></b></xsl:template>
<xsl:template match="strong"><strong><xsl:apply-templates/></strong></xsl:template>
<xsl:template match="i"><i><xsl:apply-templates/></i></xsl:template>
<xsl:template match="u"><u><xsl:apply-templates/></u></xsl:template>
<xsl:template match="sub"><sub><xsl:apply-templates/></sub></xsl:template>
<xsl:template match="sup"><sup><xsl:apply-templates/></sup></xsl:template>
<xsl:template match="nobr"><nobr><xsl:apply-templates/></nobr></xsl:template>
<xsl:template match="pop_link"><a href="#" onclick="return win_popup('{@href}',{@width},{@height});"><xsl:apply-templates/></a></xsl:template>
<xsl:template match="small"><small><xsl:apply-templates/></small></xsl:template>
<xsl:template match="p">
 <p>
	 <xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>
	 <xsl:if test="@style"> <xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
	 <xsl:if test="@align"> <xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
	 <xsl:apply-templates/>
</p>
 </xsl:template>
<xsl:template match="binary"><xsl:copy-of select="."/></xsl:template>
<xsl:template match="binary[@html]"><xsl:value-of select="." disable-output-escaping="yes"/></xsl:template>
<xsl:template match="ancor"><a name="{@name}"></a></xsl:template>
<xsl:template match="hidden"><input type="hidden" name="{@name}" value="{@value}"/></xsl:template>
<xsl:template match="copy">&#169;</xsl:template>
<xsl:template match="mark"><span class="mark"><xsl:apply-templates /></span></xsl:template>

<xsl:template match="h3">   
   <h3><xsl:apply-templates/></h3>   
</xsl:template>

<xsl:template match="script">   
	<script>
		<xsl:if test="@type"><xsl:attribute name="type"><xsl:value-of select="@type"/></xsl:attribute></xsl:if>
		<xsl:if test="@src"><xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute></xsl:if>
		<xsl:apply-templates/>
	</script>   
</xsl:template>

<xsl:template match="ul">   
   <ul class="spisok">
       <xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>
       <xsl:apply-templates/>
   </ul>
</xsl:template>

<xsl:template match="txt/h3">   
   <h3 class="linktoitem"><xsl:apply-templates/></h3>
</xsl:template>

<xsl:template match="ol">

   <ol>
	   <xsl:if test="@start">
			<xsl:attribute name="start"><xsl:value-of select="@start"/></xsl:attribute>
	   </xsl:if>
	   <xsl:apply-templates/>
   </ol>
   
</xsl:template>

<xsl:template match="dl"><dl><xsl:apply-templates/></dl></xsl:template>

<xsl:template match="bull"><img src="imgs/btn-more-b.gif" width="12" height="8" border="0" hspace="5" alt=""/></xsl:template>
<xsl:template match="head"><div class="larger bold"><xsl:apply-templates/></div></xsl:template>

<xsl:template match="txt//a[@name='redirect']">
	<a>
	<xsl:attribute name="href">/redirect/goto/?url=<xsl:value-of select="@href"/>/</xsl:attribute>
		<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
		<xsl:apply-templates />
	</a>
</xsl:template>

<xsl:template match="txt//a[@typeDoc and not(@name='redirect') and not(@class='thickbox')]">
	<xsl:variable name="type" select="translate(@typeDoc, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/>
	<img alt="" src="{/page/file_types[@type = $type]/url}"/>&#160;
	<a>
	<xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
		<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
		<xsl:apply-templates />
	</a>&#160;(<xsl:value-of select="@size"/>)
</xsl:template>

<xsl:template match="page[not(@print)]//txt//img[@class='fotoGalery']">
	<div class="gallery">
		<div class="galleryitem red">
			<div class="gi_holder">
				<div class="image">
					<div class="def tr"></div>
					<div class="def tl"></div>
					<div class="blockcontent">
						<img>
						   <xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute>
						   <xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
						   <xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
						   <xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>						   
						   <xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>						   
					   </img> 
					</div>
					<div class="def br"></div>
					<div class="def bl"></div>
				</div>
				<div class="details">
					<h2><xsl:apply-templates select="following-sibling::a" mode="inc"/></h2>
				</div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template match="txt//a[preceding-sibling::img[@class='fotoGalery']]"></xsl:template>

<xsl:template match="txt//a[preceding-sibling::img[@class='fotoGalery']]" mode="inc">
  <a>
   <xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
   <xsl:if test="@target"><xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute></xsl:if>
    <xsl:apply-templates/>
  </a>
</xsl:template>

<xsl:template match="txt//table[@border &gt; 0]">
	<!--<div class="card">-->
		 <table class="data">
<!--		   <xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>-->
<!--		   <xsl:if test="@border"><xsl:attribute name="border"><xsl:value-of select="@border"/></xsl:attribute></xsl:if>-->
		   <xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
		   <xsl:if test="@cellspacing"><xsl:attribute name="cellspacing"><xsl:value-of select="@cellspacing"/></xsl:attribute></xsl:if>
			<xsl:if test="@cellpadding"><xsl:attribute name="cellpadding"><xsl:value-of select="@cellpadding"/></xsl:attribute></xsl:if>
			<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
			<xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
		 <xsl:apply-templates/>
		 </table>
	 <!--</div>-->
</xsl:template>

<xsl:template match="txt//table[@border=0 or not(@border)]">	
	 <table>
		   <xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
		   <xsl:attribute name="border">0</xsl:attribute>
		   <xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
		   <xsl:if test="@cellspacing"><xsl:attribute name="cellspacing"><xsl:value-of select="@cellspacing"/></xsl:attribute></xsl:if>
			<xsl:if test="@cellpadding"><xsl:attribute name="cellpadding"><xsl:value-of select="@cellpadding"/></xsl:attribute></xsl:if>
			<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
			<xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
		 <xsl:apply-templates/>
	 </table>	
</xsl:template>

<xsl:template match="txt//table[@summary='data']//tr[position() = 1]">
  <thead>  
    <tr> 
       <xsl:apply-templates/>
    </tr> 
   </thead>
</xsl:template>

<xsl:template match="txt//table[@summary='data']//tr[position() = 1]/td">
    <th><xsl:apply-templates/></th>
</xsl:template>

<xsl:template match="txt//img[@border &gt; 0]">
  <div class="img">
	   <img>
		   <xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute>
		   <xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
		   <xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
		   <xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>		   
	   </img>  
  </div>
 </xsl:template>


<xsl:template match="gallery//a">
  <!--<h2>-->
		<a  class="gallery">
			<xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
			<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
			<xsl:apply-templates/></a>
	<!-- </h2>-->
</xsl:template>

<xsl:template match="gallery//a[@name='redirect']">
	<h2>
	<a>
	<xsl:attribute name="href">/redirect/goto/?url=<xsl:value-of select="@href"/>/</xsl:attribute>
		<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
		<xsl:apply-templates />
	</a>
	</h2>
</xsl:template>

<xsl:template match="th">
<td class="TableCaption">
<xsl:if test="@colspan"><xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute></xsl:if>
<xsl:if test="@rowspan"><xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="@valign"><xsl:attribute name="valign"><xsl:value-of select="@valign"/></xsl:attribute></xsl:if>
<xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>
<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
<b><xsl:apply-templates/></b>
</td></xsl:template>

<xsl:template match="tr">
<tr>
<xsl:if test="@bgcolor"><xsl:attribute name="bgcolor"><xsl:value-of select="@bgcolor"/></xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="@valign"><xsl:attribute name="valign"><xsl:value-of select="@valign"/></xsl:attribute></xsl:if>
<xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>
<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
<xsl:apply-templates select="td|th"/>
</tr>
</xsl:template>

<xsl:template match="td">
<td>
<xsl:if test="@colspan"><xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute></xsl:if>
<xsl:if test="@rowspan"><xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<xsl:if test="@valign"><xsl:attribute name="valign"><xsl:value-of select="@valign"/></xsl:attribute></xsl:if>
<xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class"/></xsl:attribute></xsl:if>
<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
<xsl:apply-templates/>
</td>
</xsl:template>

<!-- 
<xsl:template match="img">
<table width="{@width}" border="0" CELLSPACING="0" CELLPADDING="0">
<xsl:if test="@valign!=''"><xsl:attribute name="valign"><xsl:value-of select="@valign"/></xsl:attribute></xsl:if>
<xsl:if test="@align!=''"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<tr>
<xsl:if test="@align='right'"><td><img src="/i/0.gif" width="10" height="1"/><br/></td></xsl:if>
<td><xsl:if test="@palign"><xsl:attribute name="align"><xsl:value-of select="@palign"/></xsl:attribute></xsl:if>
<xsl:choose>
<xsl:when test="@zoom = 'yes'">
<a href="#">
<xsl:attribute name="onclick">zoom('<xsl:value-of select="@src1"/>');return false;</xsl:attribute>
<img src="{@src}" width="{@width}" height="{@height}" border="0" alt="{@alt}"></img></a>
</xsl:when>
<xsl:otherwise>
<img src="{@src}" width="{@width}" height="{@height}" border="0" alt="{@alt}"></img>
</xsl:otherwise>
</xsl:choose>
<xsl:if test="@align='left'"><td><img src="/i/0.gif" width="10" height="1"/><br/></td></xsl:if>
</td>
</tr>
<xsl:if test="*|text()"><tr><td class="texttitle_h4"><br/><xsl:apply-templates /></td></tr></xsl:if>
<tr><td><img src="" width="1" height="5"/><br/></td></tr>
</table>
</xsl:template>-->

<!-- 
<xsl:template match="img[@border]">
<table width="{@width}" border="0" align="left" cellpadding="0" cellspacing="0">
<xsl:if test="@align!=''"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<tr><xsl:if test="@align='right'"><td><img src="/i/0.gif" width="10" height="1"/><br/></td></xsl:if>
<td>
<table width="{@width}" border="0" cellpadding="2" cellspacing="1" bgcolor="#646464">
<tr>
<td bgcolor="#000000"><xsl:if test="@palign"><xsl:attribute name="align"><xsl:value-of select="@palign"/></xsl:attribute></xsl:if>
<xsl:choose>
<xsl:when test="@zoom = 'yes'">
<a href="#">
<xsl:attribute name="onclick">zoom('{@src1}');return false;</xsl:attribute>
<img src="{@src}" width="{@width}" height="{@height}" border="0"></img></a>
</xsl:when>
<xsl:otherwise>
<img src="{@src}" width="{@width}" height="{@height}" border="0"></img>
</xsl:otherwise>
</xsl:choose>
</td></tr></table>
<xsl:if test="*|text()"><tr><td class="texttitle_h4"><xsl:apply-templates /></td></tr></xsl:if>
</td>
<xsl:if test="@align='left'"><td><img src="/i/0.gif" width="10" height="1"/><br/></td></xsl:if>
</tr>
<tr><td colspan="2"><img src="/i/0.gif" width="1" height="10" /></td></tr>
</table>
</xsl:template>-->


<xsl:template match="img[@href]">
<a href="{@href}"><xsl:if test="@target"><xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute></xsl:if>
<img src="/images/{@src}" width="{@width}" height="{@height}" border="0"><xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if></img></a>
</xsl:template>

<!--<xsl:template match="font">
<xsl:choose>
 <xsl:when test="@size!=''">
    <font size="{@size}" color="{@color}" style="{@style}"><xsl:apply-templates /></font>
  </xsl:when>
<xsl:otherwise><font color="{@color}" style="{@style}"><xsl:apply-templates /></font></xsl:otherwise>
</xsl:choose>
</xsl:template>-->

<xsl:template match="font">
<!--<xsl:choose>
 <xsl:when test="@size!=''">
    <font size="{@size}" color="{@color}" style="{@style}"><xsl:apply-templates /></font>
  </xsl:when>
<xsl:otherwise><font color="{@color}" style="{@style}"><xsl:apply-templates /></font></xsl:otherwise>
</xsl:choose>-->
<font>
<xsl:if test="@color"><xsl:attribute name="color"><xsl:value-of select="@color"/></xsl:attribute></xsl:if>
<xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute></xsl:if>
<xsl:apply-templates />
</font>
</xsl:template>

<xsl:template match="param">
<param name="{@name}" value="{@value}" />
</xsl:template>

<xsl:template match="embed">
<embed src="{@src}" wmode="" quality="high" menu="false" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="{@width}" height="{@height}"></embed>
</xsl:template>

<xsl:template match="object">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="{@width}" height="{@height}">
	<xsl:apply-templates select="param"/>
	<xsl:apply-templates select="embed"/>
</object>
</xsl:template>

<xsl:template match="//object[param[@name='movie' and contains(@value ,'.flv')]]">
	<div id="{param[@name='movie']/@value}">
		<strong>Для проигрования данного ролика Вам необходимо скачать <a href="http://get.adobe.com/ru/flashplayer/">Flash Player.</a></strong>
	</div>
	<script type="text/javascript">
		var so = new SWFObject("/i/uppod.swf", "sotester", "500", "375", "9", "#ffffff");
		so.addParam("allowFullScreen", "true");
		so.addParam("allowScriptAccess", "always");
		so.addParam("wmode", "transparent");
		so.addParam("flashvars", "st=/css/video.txt&amp;file=<xsl:value-of select="param[@name='movie']/@value"/>");
		so.write("<xsl:value-of select="param[@name='movie']/@value"/>");		
	</script>
	
	<!--<object type="application/x-shockwave-flash" data="/i/uppod.swf" width="500" height="375">
		<param name="bgcolor" value="#ffffff" />
		<param name="allowFullScreen" value="true" />
		<param name="allowScriptAccess" value="always" />
		<param name="wmode" value="transparent" />
		<param name="movie" value="/i/uppod.swf" />
		<param name="flashvars" value="st=/css/video.txt&amp;file={param[@name='movie']/@value}" />
	</object>--> 
</xsl:template>

<xsl:template match="footnote">
  <xsl:variable name="id">
    <xsl:number level="any" count="footnote"/>
  </xsl:variable>
  <img src="/i/0.gif" width="1" height="1" id="ti_{$id}"/>
  <xsl:text disable-output-escaping="yes">&lt;</xsl:text>a href='#' class='footnote'
    onclick='return showBk(<xsl:value-of select="$id"/>, <xsl:text disable-output-escaping="yes">"</xsl:text><xsl:apply-templates select="title"/><xsl:text disable-output-escaping="yes">"</xsl:text>)'<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
    <u><xsl:apply-templates select="text"/></u>
    <sup>
      <xsl:apply-templates select="@id"/>
    </sup>
  <xsl:text disable-output-escaping="yes">&lt;</xsl:text>/a<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
</xsl:template>

<xsl:template name="section">
	<xsl:param name="current"/>
	<xsl:param name="pages"/>
	<xsl:param name="cur" select="1"/>
	<xsl:if test="$cur &lt;= $pages"><!--&#160;-->
		<xsl:choose>
			<xsl:when test="$cur != $current">
				<xsl:variable name="url">
					<xsl:call-template name="section_url">
						<xsl:with-param name="cur" select="$cur"/>
					</xsl:call-template>
				</xsl:variable>
				<xsl:variable name="first_url">
					<xsl:call-template name="section_first_url">
						<xsl:with-param name="cur" select="$cur"/>
					</xsl:call-template>
				</xsl:variable>
				<xsl:choose>
					<xsl:when test="$cur!=1">
						<li>
							<a>
								<xsl:attribute name="href"><xsl:value-of select="$url"/>page/<xsl:value-of select="$cur"/>/</xsl:attribute>
								<xsl:value-of select="$cur"/>
							</a>
						</li>
					</xsl:when>
					<xsl:otherwise><li><a href="{$first_url}"><xsl:value-of select="$cur"/></a></li></xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:otherwise><li class="active"><span><xsl:value-of select="$cur"/></span></li></xsl:otherwise>
		</xsl:choose>
	
		<xsl:call-template name="section">
			<xsl:with-param name="cur" select="$cur+1"/>
			<xsl:with-param name="current" select="$current"/>
			<xsl:with-param name="pages" select="$pages"/>
		</xsl:call-template>	
	</xsl:if>
</xsl:template>



<xsl:template match="section">
	<xsl:param name="colspan"/>
	<xsl:param name="style"/>
	<xsl:variable name="pages">
		<xsl:choose>
			<xsl:when test="number(@pcount) &lt;= 7">
					<xsl:value-of select="number(@pcount)"/>
			</xsl:when>
			<xsl:when test="(number(@page) + 3) &lt; number(@pcount) and (number(@page) + 3) &lt;= 7">
					<xsl:value-of select="7"/>
			</xsl:when>
			<xsl:when test="(number(@page) + 3) &lt; number(@pcount)">
					<xsl:value-of select="number(@page) + 3"/>
			</xsl:when>
			<xsl:otherwise>
					<xsl:value-of select="number(@pcount)"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="start_number">
		<xsl:choose>
			<xsl:when test="number(@pcount) &lt;= 7">
				<xsl:value-of select="1"/>
			</xsl:when>
			<xsl:when test="(number(@page) - 3) &lt; 1">
				<xsl:value-of select="1"/>
			</xsl:when>
			<xsl:when test="(number(@pcount) - (number(@page) - 3)) &lt; 6">
				<xsl:value-of select="number(@pcount) - 6"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="number(@page) - 3"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:if test="/page/data/psizes!='' or @pcount &gt; 1">
		<xsl:choose>
				<xsl:when test="@pcount &gt; 1">
						<xsl:choose>
								<xsl:when test="@page &gt; 1">
										<xsl:variable name="url">
											<xsl:choose>
												<xsl:when test="(@page-1) = 1">
													<xsl:call-template name="section_first_url">
															<xsl:with-param name="cur" select="@page-1"/>
													</xsl:call-template>
												</xsl:when>
												<xsl:otherwise>
													<xsl:call-template name="section_url">
															<xsl:with-param name="cur" select="@page-1"/>
													</xsl:call-template>												
												</xsl:otherwise>
											</xsl:choose>
										</xsl:variable>
										<li class="prev">
											<a href="#">
												<xsl:attribute name="href"><xsl:value-of select="$url"/><xsl:if test="(@page-1) &gt; 1">page/<xsl:value-of select="number(@page)-1"/>/</xsl:if></xsl:attribute>
											</a>
										</li>
								</xsl:when>
								<xsl:otherwise>
									<li class="prev"></li>
								</xsl:otherwise>
						</xsl:choose>
						<xsl:if test="number(@pcount) &gt; 7 and $start_number &gt; 1">
						<xsl:variable name="previous"><xsl:value-of select="$start_number - 1"/></xsl:variable>
						<!-- Первые 3 страницы -->
						  <xsl:apply-templates select="first_pages">
						  <xsl:with-param name="pcount" select="@pcount"/>
						  <xsl:with-param name="count" select="@count"/>
						  </xsl:apply-templates>
						  <!-- Первые 3 страницы -->
						  <xsl:choose>
						  <xsl:when test="$previous &gt; 4">
							  <li><a>
							  <xsl:attribute name="href"><xsl:variable name="prev_number"><xsl:value-of select="$start_number - 1"/></xsl:variable><xsl:variable name="url"><xsl:call-template name="section_url"><xsl:with-param name="cur" select="$prev_number"/></xsl:call-template></xsl:variable><xsl:value-of select="$url"/>page/<xsl:value-of select="$prev_number"/>/<!--pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/--><xsl:if test="/page/data/section/@psize &gt; 0">psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute>...</a></li>
						  </xsl:when>
						  <xsl:when test="$previous=4">
							  <li><a>
							  <xsl:attribute name="href"><xsl:variable name="prev_number"><xsl:value-of select="$start_number - 1"/></xsl:variable><xsl:variable name="url"><xsl:call-template name="section_url"><xsl:with-param name="cur" select="$prev_number"/></xsl:call-template></xsl:variable><xsl:value-of select="$url"/>page/<xsl:value-of select="$prev_number"/>/<!--pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/--><xsl:if test="/page/data/section/@psize &gt; 0">psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute><xsl:value-of select="$previous"/></a></li>
						  </xsl:when>
						  </xsl:choose>

						  </xsl:if>
						<xsl:call-template name="section">
							<xsl:with-param name="current" select="@page"/>
							<xsl:with-param name="pages" select="$pages"/>
							<xsl:with-param name="cur" select="$start_number"/>
						</xsl:call-template>
						
						<xsl:if test="number(@pcount) &gt; 7 and $pages &lt; number(@pcount)">
						<xsl:variable name="next"><xsl:value-of select="$pages + 1"/></xsl:variable>
						<xsl:variable name="next2"><xsl:value-of select="$pages + 2"/></xsl:variable>
						
						<xsl:choose>
							<xsl:when test="$next &lt; last_pages[index=0]/lpg">
						<xsl:choose>
						<xsl:when test="$next2=last_pages[index=0]/lpg">
						<li><a>
						  <xsl:attribute name="href"><xsl:variable name="next_number"><xsl:value-of select="$pages + 1"/></xsl:variable><xsl:variable name="url"><xsl:call-template name="section_url"><xsl:with-param name="cur" select="$next_number"/></xsl:call-template></xsl:variable><xsl:value-of select="$url"/>page/<xsl:value-of select="$next_number"/>/<!--pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/--><xsl:if test="/page/data/section/@psize &gt; 0">/psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute><xsl:value-of select="$next"/></a></li>
						</xsl:when>
						<xsl:otherwise>
						  <li><a>
						  <xsl:attribute name="href"><xsl:variable name="next_number"><xsl:value-of select="$pages + 1"/></xsl:variable><xsl:variable name="url"><xsl:call-template name="section_url"><xsl:with-param name="cur" select="$next_number"/></xsl:call-template></xsl:variable><xsl:value-of select="$url"/>page/<xsl:value-of select="$next_number"/>/<!--pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/--><xsl:if test="/page/data/section/@psize &gt; 0">/psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute>...</a></li>
						</xsl:otherwise>
						</xsl:choose>
						<!-- Последние 3 страницы -->
						<xsl:apply-templates select="last_pages">
						  <xsl:with-param name="pcount" select="@pcount"/>
						  <xsl:with-param name="count" select="@count"/>
						  </xsl:apply-templates>
						  <!-- Последние 3 страницы -->
						  </xsl:when>
						  <xsl:otherwise>
							  <!-- Оставшиеся страницы -->
								<xsl:apply-templates select="last_pages[lpg &gt; $pages]">
								  <xsl:with-param name="pcount" select="@pcount"/>
								  <xsl:with-param name="count" select="@count"/>
								  </xsl:apply-templates>
						  <!-- Оставшиеся  страницы -->
						  </xsl:otherwise>
						  </xsl:choose>
						  
							 <!-- next=<xsl:value-of select="$next"/>,
							  pages=<xsl:value-of select="$pages"/>,-->
						  
						  <!--
						  <li><a>
						  <xsl:attribute name="href"><xsl:variable name="next_number"><xsl:value-of select="$pages + 1"/></xsl:variable><xsl:variable name="url"><xsl:call-template name="section_url"><xsl:with-param name="cur" select="$next_number"/></xsl:call-template></xsl:variable><xsl:value-of select="$url"/>page/<xsl:value-of select="$next_number"/>/pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/<xsl:if test="/page/data/section/@psize &gt; 0">/psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute>...</a></li>
						  <xsl:variable name="next"><xsl:value-of select="$pages + 1"/></xsl:variable>
						  -->
						  
						<!-- Последние 3 страницы -->
						  <!--<xsl:apply-templates select="last_pages">
						  <xsl:with-param name="pcount" select="@pcount"/>
						  <xsl:with-param name="count" select="@count"/>
						  </xsl:apply-templates>-->
						  <!-- Последние 3 страницы -->
						</xsl:if>
						<xsl:choose>
								<xsl:when test="@page &lt; @pcount">
										<xsl:variable name="url">
												<xsl:call-template name="section_url">
														<xsl:with-param name="cur" select="@page+1"/>
												</xsl:call-template>
										</xsl:variable>
												<li class="next"><a>
														<xsl:attribute name="href"><xsl:value-of select="$url"/>page/<xsl:value-of select="@page+1"/>/<!--pcount/<xsl:value-of select="/page/data/section/@pcount"/>/count/<xsl:value-of select="/page/data/section/@count"/>/--><xsl:if test="/page/data/section/@psize &gt; 0">/psize/<xsl:value-of select="/page/data/section/@psize"/>/</xsl:if></xsl:attribute><!--<img src="/i/pages-hover-right-link.gif" width="11" height="11" alt="" />-->&#187;</a></li>
										</xsl:when>
								<xsl:otherwise></xsl:otherwise>
						</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
				 &#160;</xsl:otherwise>
		</xsl:choose>
	</xsl:if>
</xsl:template>
        
<xsl:template match="section/first_pages">
	<xsl:param name="pcount"/>
	<xsl:param name="count"/>
	<li><a>
	<xsl:attribute name="href"><xsl:call-template name="section_url"/>page/<xsl:apply-templates select="fpg"/>/</xsl:attribute>
	<xsl:apply-templates select="fpg"/>
	</a></li>
</xsl:template>

<xsl:template match="section/last_pages">
	<xsl:param name="pcount"/>
	<xsl:param name="count"/>
	<li><a>
	<xsl:attribute name="href"><xsl:call-template name="section_url"/>page/<xsl:apply-templates select="lpg"/>/</xsl:attribute>
	<xsl:apply-templates select="lpg"/>
	</a></li>
</xsl:template>

<xsl:template name="section_url">
  <xsl:param name="cur" select="1"/>
</xsl:template>


<xsl:template match="copy_of/*"><xsl:copy-of select="." /></xsl:template>


<xsl:template match="iframe"><xsl:copy-of select="." /></xsl:template>


<xsl:template match="banner">
        <xsl:attribute name="style">
                <xsl:if test="background_color != ''">
                        background-color: <xsl:value-of select="background_color" />;
                </xsl:if>
                <xsl:if test="background_image/w &gt; 0">
                        background-image: url(/images/banners/<xsl:value-of select="background_image/src"/>);
                </xsl:if>
                <xsl:choose>
                        <xsl:when test="background_align = '0'">background-position: top left;</xsl:when>
                        <xsl:when test="background_align = '1'">background-position: center left;</xsl:when>
                        <xsl:when test="background_align = '2'">background-position: bottom left;</xsl:when>
                        <xsl:when test="background_align = '3'">background-position: top right;</xsl:when>
                        <xsl:when test="background_align = '4'">background-position: center right;</xsl:when>
                        <xsl:when test="background_align = '5'">background-position: bottom right;</xsl:when>
                        <xsl:when test="background_align = '6'">background-position: top center;</xsl:when>
                        <xsl:when test="background_align = '7'">background-position: center center;</xsl:when>
                        <xsl:when test="background_align = '8'">background-position: bottom center;</xsl:when>
                </xsl:choose>      
                <xsl:choose>
                        <xsl:when test="background_repeat = '0'">background-repeat: no-repeat;</xsl:when>
                        <xsl:when test="background_repeat = '2'">background-repeat: repeat-x;</xsl:when>
                        <xsl:when test="background_repeat = '3'">background-repeat: repeat-y;</xsl:when>
                </xsl:choose>
                <xsl:value-of select="style"/>
        </xsl:attribute>
        <xsl:choose>
                <xsl:when test="type = '0'"> <!-- Image -->
                        <xsl:choose>
                                <xsl:when test="image/w &gt; 0 and url != ''">
                                        <a href="{url}" title="{alt}">
                                                <xsl:if test="newwin = '1'">
                                                        <xsl:attribute name="target">_blank</xsl:attribute>
                                                </xsl:if>
                                                <img src="/images/banners/{image/src}" width="{image/w}" height="{image/h}" alt="{alt}" title="{alt}" border="0"/>
                                        </a>
                                </xsl:when>
                                <xsl:when test="image/w &gt; 0">
                                        <img src="/images/banners/{image/src}" width="{image/w}" height="{image/h}" alt="{alt}" title="{alt}" />
                                </xsl:when>
                        </xsl:choose>
                </xsl:when>
                <xsl:when test="type = '1'"> <!-- Flash -->
                        <xsl:variable name="width">
                                <xsl:choose>
                                        <xsl:when test="width &gt; 0"><xsl:value-of select="width" /></xsl:when>
                                        <xsl:otherwise><xsl:value-of select="image/w" /></xsl:otherwise>
                                </xsl:choose>
                        </xsl:variable>
                        <xsl:variable name="height">
                                <xsl:choose>
                                        <xsl:when test="height &gt; 0"><xsl:value-of select="height" /></xsl:when>
                                        <xsl:otherwise><xsl:value-of select="image/h" /></xsl:otherwise>
                                </xsl:choose>
                        </xsl:variable>
                        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="{$width}" height="{$height}">
                          <param name="movie" value="/images/banners/{image/src}" />
                          <param name="menu" value="false" />
                          <param name="quality" value="high" />
                          <param name="devicefont" value="true" />
                          <param name="bgcolor" value="#FFFFFF" />
                          <embed src="/images/banners/{image/src}" menu="false" quality="high" devicefont="true" bgcolor="#FFFFFF" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" width="{$width}" height="{$height}" />
                        </object>
                </xsl:when>
                <xsl:when test="type = '2'"> <!-- Text -->
                        <xsl:apply-templates select="description" />
                </xsl:when>
        </xsl:choose>
</xsl:template>

</xsl:stylesheet>


