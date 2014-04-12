<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="_main.xsl"/>
	
	<xsl:template name="javaScript">
		<script src="/js/booklet/jquery.booklet.latest.min.js" type="text/javascript"></script>
		<link href="/js/booklet/jquery.booklet.latest.css" type="text/css" rel="stylesheet" media="screen, projection, tv" />
		
		<script src="/js/jpageflipper/jquery.pageFlipper.js" type="text/javascript"></script>
		
		<script type="text/javascript">
		<![CDATA[
			$(document).ready(function(){
				$(".various").fancybox({
					fitToView	: false,
					width		: '640px',
					height		: '480px',
					autoSize	: false,
					closeClick	: false,
					openEffect	: 'none',
					closeEffect	: 'none'
				}); 
				
				$('.booklets_book').booklet({
					width: 640,
					height: 480
				});
				
				var isIPad = navigator.userAgent.indexOf('iPad') >= 0;

				$('#lstImages').pageFlipper({
					fps: isIPad ? 10 : 20,
					easing: isIPad ? 0.3 : 0.2,
					backgroundColor: '#aaaaaa'
				});

				$('.canvasHolder').css('left', (isIPad ? 0 : 130) + 'px');
				$('#mouse').css({
					width: (isIPad ? 40 : 20) + 'px',
					height: (isIPad ? 40 : 20) + 'px',
					'-moz-border-radius': (isIPad ? 20 : 10) + 'px',
					'-webkit-border-radius': (isIPad ? 20 : 10) + 'px'
				});
			});
		]]>
		</script>
	</xsl:template>
	
	<xsl:template match="booklets">
		<li>
			<div class="img">
				<a href="#inline{@id}" class="various"><img src="/images/booklets/{image_name/@src}" alt="{name}"/></a>
			</div>
			<div class="name">
				<a href="{result_path/@src}"><xsl:value-of select="name"/></a> (<xsl:value-of select="result_path/@size"/>)
			</div>
		</li>
	</xsl:template>
	
	<xsl:template match="booklets" mode="jpageflipper">
		<li>
			<div class="img">
				<a href="#jpageflipper-inline{@id}" class="various"><img src="/images/booklets/{image_name/@src}" alt="{name}"/></a>
			</div>
			<div class="name">
				<a href="{result_path/@src}"><xsl:value-of select="name"/></a> (<xsl:value-of select="result_path/@size"/>)
			</div>
		</li>
	</xsl:template>
	
	<xsl:template match="booklets" mode="div">
		<div class="booklets_book" id="inline{@id}" style="display:none;">
			<xsl:apply-templates select="booklets_pages"/>
		</div>		
	</xsl:template>
	
	<xsl:template match="booklets" mode="div-jpageflipper">
		<div id="jpageflipper-inline{@id}" style="display:none;">
			<ul class="booklets_jpageflipper">
				<xsl:apply-templates select="booklets_pages" mode="jpageflipper"/>
			</ul>			
		</div>
	</xsl:template>
	
	<xsl:template match="booklets_pages">
		<div>
			<xsl:apply-templates select="description"/>
		</div>
	</xsl:template>
	
	<xsl:template match="booklets_pages" mode="jpageflipper">
		<li>
			<xsl:apply-templates select="description"/>
		</li>
	</xsl:template>
	
	<xsl:template match="data">
		<div class="forprint">
			<h1>
				<xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/>
			</h1>
		</div>
		<div class="text">
			<ul class="booklets">
				<xsl:apply-templates select="booklets"/>
			</ul>
			
			<ul class="booklets">
				<xsl:apply-templates select="booklets" mode="jpageflipper"/>
			</ul>
			
			<xsl:apply-templates select="booklets" mode="div"/>
			<xsl:apply-templates select="booklets" mode="div-jpageflipper"/>
		</div>
	</xsl:template>
</xsl:stylesheet>
