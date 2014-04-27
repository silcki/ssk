<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="../_feedbackForm.xsl"/>
	<xsl:import href="../_main.xsl"/>
	<xsl:template name="javaScript">
		<script type="text/javascript" src="/js/jquery.validate.js"/>
		<script type="text/javascript" src="/js/validate.js?v=0"/>
		<link rel="stylesheet" type="text/css" href="/css/jcarousel.css"/>
		<script type="text/javascript" src="/js/jcarousel.js"/>
	</xsl:template>
	<xsl:template match="item_photos">
		<li>
			<a href="/images/gallery/{image2/@src}" rel="gallery" title="{name}">
				<img src="/images/gallery/{image1/@src}" alt="{name}"/>
			</a>
		</li>
	</xsl:template>
	<xsl:template match="item_photos" mode="jcar">
		<li class="jcarousel-item jcarousel-item-horizontal">
			<a href="/images/gallery/{image2/@src}" rel="gallery" title="{name}">
				<img src="/images/gallery/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}"/>
			</a>
		</li>
	</xsl:template>
	<xsl:template match="//data/item/elements">
		<li id="area{@name_num}">
			<div class="backtopholder">
				<span>
					<xsl:value-of select="@name_num"/>
				</span>
				<img src="/images/item_elem/{image1/@src}" alt="{name}" width="{image1/@w}" height="{image1/@h}"/>
				<p>
					<strong>
						<xsl:value-of select="name"/>
					</strong> — <xsl:value-of select="description"/>
				</p>
				<a href="back" class="back" title="{/page/form_back_to_image}">
					<xsl:value-of select="/page/form_back_to_image"/>
				</a>
			</div>
		</li>
	</xsl:template>
	<xsl:template match="error_messages">
		<li>
			<xsl:value-of select="err_mess"/>
		</li>
	</xsl:template>
	<!--<xsl:variable name="validate"><![CDATA[
<script>
$().ready(function() { 
  $("#feedbackk").validate({
		errorLabelContainer: $("#feedbackk div.errhold"),
		submitHandler: function(form) {
			data = $('form').serialize();
			var url = window.location.href;
			$.post(url, data, function(data){
				//$("#feedbackk fieldset").prepend('<div class="okhold">Ваше сообщение было успешно отправлено</div>');
				$.fancybox(data.text);
				 form.reset();
			});
		},
		rules: {
			captcha: {
				required: true,
				remote: "/ajax/validatecaptcha/"
			},
			name: {
				required: true,
				minlength: 2
			},
			telmob: {
				required: true,
				minlength: 2
			}		
		},
		messages: {
			name: "Поле Имя пустое",
			telmob: "Поле Телефон пустое",
			captcha: "Укажите правильные символы на картинке."	
		},
		onkeyup: false
	});
	
	 
});
</script>]]>						
</xsl:variable>-->
	<xsl:template match="txt//img[@class='sskgallery']">
		<xsl:if test="count(//item/item_photos) &gt; 0">
			<p class="linktoitem">
				<xsl:value-of select="/page/text_item_photo"/>
			</p>
			<xsl:choose>
				<xsl:when test="count(//item/item_photos) &gt; 4">
					<ul class="photo">
						<xsl:apply-templates select="//item/item_photos"/>
					</ul>
					<script>
					$('.photo').jcarousel({
						scroll: 3,
						start: 1
					});
				</script>
				</xsl:when>
				<xsl:otherwise>
					<div class="jcarousel-container jcarousel-container-horizontal" style="display: block;">
						<div class="jcarousel-clip jcarousel-clip-horizontal">
							<ul class="photo jcarousel-list jcarousel-list-horizontal">
								<xsl:apply-templates select="//item/item_photos" mode="jcar"/>
							</ul>
						</div>
					</div>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
	</xsl:template>
	<xsl:template match="data">
		<div class="forprint">
			<h1>
				<xsl:value-of select="//data/item/name"/>
			</h1>
			<p id="print">
				<a title="{/page/print_text}" href="#" onclick="window.print();">
					<xsl:value-of select="/page/print_text"/>
				</a>
			</p>
		</div>
		<div class="text">
			<xsl:apply-templates select="item/pop_image_text/txt"/>
		</div>
		<xsl:if test="//data/item/image1/@src!=''">
			<div class="img">
				<img src="/images/it/{//data/item/image1/@src}" width="{//data/item/image1/@w}" height="{//data/item/image1/@h}" alt="" border="0" usemap="#Map"/>
				<xsl:value-of select="//data/item/code_map_area" disable-output-escaping="yes"/>
				<xsl:if test="//data/item/image2/@src!=''">
					<a href="/images/it/{//data/item/image2/@src}" class="zoom" title="{//data/item/name}">увеличить</a>
				</xsl:if>
			</div>
		</xsl:if>
		<!--<div class="callback zakaz">
		<div class="heading">
			<p><a href="#">Задать новый вопрос</a></p>
		</div>
	</div>-->
		<div class="text">
			<xsl:apply-templates select="item/under_image_text/txt"/>
		</div>
		<xsl:if test="count(//data/item/elements) &gt; 0">
			<h3>
				<xsl:value-of select="/page/item_text"/>
			</h3>
			<ul class="stelazhmore">
				<xsl:apply-templates select="//data/item/elements"/>
			</ul>
		</xsl:if>
		<div class="text">
			<xsl:apply-templates select="item/txt"/>
		</div>
		<xsl:if test="//data/item/@is_form=1">
			<!--<script type='text/javascript' src='/js/jquery.validate.js'></script>				
		<xsl:value-of select="$validate" disable-output-escaping="yes"/>-->
			<div class="callback question">
				<div class="heading">
					<p>
						<a>
							<xsl:value-of select="/page/zakaz_stellag"/>
						</a>
					</p>
				</div>
				<p>
					<xsl:value-of select="/page/banner_item_form/description"/>
				</p>
				<xsl:call-template name="formFeedback">
					<xsl:with-param name="url">/ajax/sendrequest/item/<xsl:value-of select="item/@item_id"/>/catalogue/<xsl:value-of select="item/@catalogue_id"/>/</xsl:with-param>
				</xsl:call-template>
			</div>
			<!--<xsl:if test="was_send=1">
			<script>
			$(document).ready(function(){
				$("body").scrollTo( $(".feedback-form"), 800);
			});
		</script>
		</xsl:if>-->
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>
