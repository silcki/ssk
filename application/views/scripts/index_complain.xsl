<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="page">
	<div class="callback phoneback complain">
<!--		<div class="heading">
			<p style="background:url(/images/textes/{/page/text_callback_sendyournumber/@src}) no-repeat;"><xsl:value-of select="/page/text_callback_sendyournumber"/></p>
		</div>-->
		<form class="feedback-form" id="complain-phoneback" action="#" method="post">
			<fieldset>
				<div class="errhold"></div>
				<table>
					<col width="65" />
					<tr>
						<td><label><strong><xsl:value-of select="/page/text_complain_name"/></strong>:<span class="mark">*</span></label></td>
						<td><span class="input">
							<input type="text" name="name" class="text" />
							</span></td>
					</tr>
					<tr>
						<td><label><strong><xsl:value-of select="/page/text_complain_phone"/></strong>:</label></td>
						<td><span class="input">
							<input type="text" name="phone" class="text" />
							</span></td>
					</tr>
					<tr>
						<td><label><strong><xsl:value-of select="/page/text_complain_email"/></strong>:</label></td>
						<td><span class="input">
							<input type="text" name="email" class="text" />
							</span></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: left; padding-top: 10px;">
							<p><strong><xsl:value-of select="/page/text_complain_message"/>:<span class="mark">*</span></strong></p>
							<span class="input" style="margin-left: 0px;"><textarea id="description" name="description" rows="7" cols="50" class="text"></textarea></span>
						</td>
					</tr>
				</table>
				<div class="row"> <a class="btn_send" id="sendcomplain" href="/index/complain/"><xsl:value-of select="/page/text_complain_send"/></a> </div>
			</fieldset>
		</form>
	</div>
</xsl:template>

</xsl:stylesheet>