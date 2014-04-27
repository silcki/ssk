<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="../_main.xsl"/>
	
	<xsl:template match="/page/main_menu/main_children_menu">
		<li>
			<a href="{url}">
				<xsl:value-of select="name"/>
			</a>
		</li>
	</xsl:template>
	
<xsl:variable name="validate"><![CDATA[
<script>
$().ready(function() { 
  $("#feedbackk").validate({
		errorLabelContainer: $("#feedbackk div.errhold"),
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
			captcha: "Укажиет правильные символы на картинке."	
		},
		onkeyup: false
	});
});
</script>]]>						
</xsl:variable>	

	
	<xsl:template match="data">		
		<script type='text/javascript' src='/js/jquery.validate.js'></script>				
		<xsl:value-of select="$validate" disable-output-escaping="yes"/>			
		<div class="forprint">
			<h1><xsl:value-of select="docinfo/name"  disable-output-escaping="yes"/></h1>	
		</div>
		<div class="text">
			<script>
				var form_fields_error = '<xsl:value-of select="/page/form_fields_error"/>';
			</script>
			<xsl:apply-templates select="docinfo/txt"/>
		</div>
	</xsl:template>
</xsl:stylesheet>
