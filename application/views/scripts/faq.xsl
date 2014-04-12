<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="_feedbackForm.xsl"/>
	<xsl:import href="_main.xsl"/>
	<xsl:template name="javaScript">
		<script type="text/javascript" src="/js/jquery.validate.js"/>
		<script type="text/javascript" src="/js/validate.js?v=0"/>
	</xsl:template>
	<xsl:template match="faq_group">
		<p class="zagol">
			<xsl:value-of select="name"/>
		</p>
		<xsl:apply-templates select="faq"/>
		<br/>
		<br/>
	</xsl:template>
	<xsl:template match="faq">
		<div class="faq">
			<div class="ask">
				<p>
					<a href="#">
						<xsl:value-of select="question" disable-output-escaping="yes"/>
					</a>
				</p>
			</div>
			<div class="text">
				<xsl:apply-templates select="answer"/>
			</div>
		</div>
	</xsl:template>
<!--	<xsl:variable name="validate"><![CDATA[
<script>
$().ready(function() {
  $("#feedbackk").validate({
		errorLabelContainer: $("#feedbackk div.errhold"),
		rules: {
			captcha: {
				required: true,
				remote: "/index/caphainp/"
			},
			name: {
				required: true,
				minlength: 2
			},
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			name: "Поле Имя пустое",
			email: "Поле Email пустое или заполнено не правильно",
			captcha: "Укажите правильные символы на картинке."
		},
		onkeyup: false
	});
});
</script>]]></xsl:variable>-->
	<xsl:template match="data">
<!--		<script type="text/javascript" src="/js/jquery.validate.js"/>
		<xsl:value-of select="$validate" disable-output-escaping="yes"/>-->
		<div class="forprint">
			<h1>
				<xsl:value-of select="/page/docinfo/name" disable-output-escaping="yes"/>
			</h1>
			<p id="print">
				<a title="{/page/print_text}" href="#" onclick="window.print();">
					<xsl:value-of select="/page/print_text"/>
				</a>
			</p>
		</div>
		<div class="text">
			<xsl:apply-templates select="/page/docinfo/txt"/>
		</div>
		<div class="faqs">
			<xsl:apply-templates select="faq_group"/>
		</div>
		<xsl:if test="//say_question">
			<div class="callback question" id="faq_id">
				<div class="heading">
					<p>
						<a style='background: url("/i/faq_query.png") repeat scroll 0pt 0pt transparent !important;'>
							<xsl:value-of select="/page/say_question"/>
						</a>
					</p>
				</div>
                <div class="loadingForm">
				<form class="feedback-form" action="/ajax/sendquestion/" id="feedbackk" method="post">
					<fieldset>
						<div class="errhold"/>
						<xsl:if test="was_send=1">
							<div class="okhold">Ваше сообщение было успешно отправлено</div>
						</xsl:if>
						<table>
							<col width="118"/>
							<tr>
								<td>
									<label>
										<xsl:value-of select="/page/form_name"/>
										<span class="mark">*</span>
									</label>
								</td>
								<td>
									<span class="input">
										<input type="text" name="name" class="text" id="name"/>
									</span>
								</td>
							</tr>
							<tr>
								<td>
									<label>
										<xsl:value-of select="/page/form_email"/>
										<span class="mark">*</span>
									</label>
								</td>
								<td>
									<span class="input">
										<input type="text" name="email" id="email" class="text"/>
									</span>
								</td>
							</tr>
							<tr>
								<td>
									<label>
										<xsl:value-of select="/page/faq_text"/>
									</label>
								</td>
								<td>
									<span class="input">
										<textarea class="text" cols="50" rows="7" name="faq_text" id="faq_text"/>
									</span>
								</td>
							</tr>
							<tr>
								<td/>
								<td>
									<span id="capcha">
										<script>reloadCaptcha();</script>
									</span>
									<a href="javascript:void();" onclick="reloadCaptcha(); return false;" id="reloadcapcha">
										<xsl:value-of select="/page/form_refresh"/>
									</a>
								</td>
							</tr>
							<tr>
								<td>
									<label>
										<xsl:value-of select="/page/form_captcha"/>
									</label>
								</td>
								<td>
									<span class="input">
										<input type="text" name="captcha" class="text" id="captcha"/>
									</span>
								</td>
							</tr>
						</table>
						<div class="row">
							<a class="btn_send" id="zakaz" href="javascript:void(0);">
								<xsl:value-of select="/page/form_button_send"/>
							</a>
						</div>
					</fieldset>
				</form>
                </div>
				<xsl:if test="was_send=1">
					<script>
				$(document).ready(function(){
					$("body").scrollTo( $(".feedback-form"), 800);
				});
			</script>
				</xsl:if>
			</div>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>
