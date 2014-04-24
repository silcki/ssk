<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match="callback_time">
		<li>
			<input type="radio" id="cost{@id}" class="btnradio" name="callback_time_id" value="{@id}">
				<xsl:if test="position()=1">
					<xsl:attribute name="checked">checked</xsl:attribute>
				</xsl:if>
			</input>
			<label for="cost{@id}">
				<xsl:choose>
					<xsl:when test="position()=1">
						<xsl:attribute name="class">act</xsl:attribute>
					</xsl:when>
					<xsl:otherwise><xsl:attribute name="class">unact</xsl:attribute></xsl:otherwise>
				</xsl:choose>
				<xsl:value-of select="name"/></label>
		</li>
	</xsl:template>

<xsl:template match="page">
	<div class="callback phoneback">
<!--		<div class="heading">
			<p style="background:url(/images/textes/{/page/text_callback_sendyournumber/@src}) no-repeat;"><xsl:value-of select="/page/text_callback_sendyournumber"/></p>
		</div>-->
		<form class="feedback-form" id="callback-phoneback" action="#" method="post">
			<fieldset>
				<div class="errhold"><ul></ul></div>
				<table>
					<col width="65" />
					<tr>
						<td><label><strong><xsl:value-of select="/page/text_callback_name"/></strong>:<span class="mark">*</span></label></td>
						<td><span class="input">
							<input type="text" name="name" class="text" />
							</span></td>
					</tr>
					<tr>
						<td><label><strong><xsl:value-of select="/page/text_callback_phone"/></strong>:<span class="mark">*</span></label></td>
						<td><span class="input">
							<input type="text" name="phone" class="text" />
							</span></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: left; padding-top: 10px;">
							<p><strong><xsl:value-of select="/page/text_callback_timeforcall"/>:</strong></p>
							<ul class="callback_time">
								<xsl:apply-templates select="callback_time"/>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: left; padding-top: 10px;">
							<p><strong><xsl:value-of select="/page/text_callback_message"/>:</strong></p>
							<span class="input" style="margin-left: 0px;"><textarea id="description" name="description" rows="7" cols="50" class="text"></textarea></span>
						</td>
					</tr>
				</table>
				<div class="row"> <a class="btn_send" id="sendphone" href="/ajax/callback/"><xsl:value-of select="/page/text_callback_send"/></a> </div>
			</fieldset>
		</form>
	</div>
</xsl:template>

</xsl:stylesheet>