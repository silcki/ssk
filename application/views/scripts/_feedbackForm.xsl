<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template name="javaScript">
		<script type="text/javascript" src="/js/jquery.validate.js"/>
		<script type="text/javascript" src="/js/validate.js?v=0"/>
	</xsl:template>
	<xsl:template name="formFeedback">
	    <xsl:param name="url"/>
<!--		<form class="feedback-form" action="/ajax/sendfeedback/" id="feedbackk" method="post" enctype="multipart/form-data">-->
    <div class="loadingForm">
<!--        <div class="phoneback_back">
          <div class="phoneback_back_loader"> </div>
        </div>-->
		<form class="feedback-form" action="{$url}" id="feedbackk" method="post" enctype="multipart/form-data">
			<fieldset>
				<div class="errhold"/>
				<xsl:if test="//data/was_send=1">
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
								<xsl:value-of select="/page/form_phone"/>
								<span class="mark">*</span>
							</label>
						</td>
						<td>
							<span class="input">
								<input type="text" id="telmob" name="telmob" class="text"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>
								<xsl:value-of select="/page/form_description"/>
							</label>
						</td>
						<td>
							<span class="input">
								<textarea class="text" cols="50" rows="7" name="description" id="description"/>
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="more_parent">
								<a href="#" class="more_link">
									<span>больше параметров</span>
								</a>
								<div class="more_block">
									<table>
										<col width="118"/>
										<!--<tr>
									<td><label><xsl:value-of select="/page/form_lastname"/></label></td>
									<td><span class="input"><input type="text" id="surname" name="surname"  class="text" /></span></td>
								</tr>-->
										<tr>
											<td>
												<label>
													<xsl:value-of select="/page/form_email"/>
												</label>
											</td>
											<td>
												<span class="input">
													<input type="text" name="email" class="text"/>
												</span>
											</td>
										</tr>
										<tr>
											<td>
												<label>
													<xsl:value-of select="/page/form_city"/>
												</label>
											</td>
											<td>
												<span class="input">
													<input type="text" name="city" class="text"/>
												</span>
											</td>
										</tr>
										<tr>
											<td>
												<label>
													<xsl:value-of select="/page/form_company"/>
												</label>
											</td>
											<td>
												<span class="input">
													<input type="text" name="company" class="text"/>
												</span>
											</td>
										</tr>
										<tr>
											<td>
												<label>
													<xsl:value-of select="/page/feed_attach"/>
												</label>
											</td>
											<td>
												<span class="input">
													<input type="file" name="feed_attach" class="text"/>
												</span>
												<span class="spanfile">
													<xsl:value-of select="/page/banner_attach_file/description"/>
												</span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td/>
						<td>
							<span id="capcha">
								<script type="text/javascript">reloadCaptcha();</script>
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
	</xsl:template>
</xsl:stylesheet>
