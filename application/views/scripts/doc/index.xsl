<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "../symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="../_feedbackForm.xsl"/>
	<xsl:import href="../_main.xsl"/>
	<xsl:template name="javaScript">
		<script type="text/javascript" src="/js/jquery.validate.js"/>
		<script type="text/javascript" src="/js/validate.js?v=0"/>
	</xsl:template>
	<xsl:template match="/page/main_menu/main_children_menu">
		<li>
			<a href="{url}">
				<xsl:value-of select="name"/>
			</a>
		</li>
	</xsl:template>
	<xsl:template match="data">
		<div class="forprint">
			<h1>
				<xsl:value-of select="docinfo/name" disable-output-escaping="yes"/>
			</h1>
			<p id="print">
				<a title="{/page/print_text}" href="#" onclick="window.print();">
					<xsl:value-of select="/page/print_text"/>
				</a>
			</p>
		</div>
		<div class="text">
			<script>
				var form_fields_error = '<xsl:value-of select="/page/form_fields_error"/>';
			</script>
			<xsl:apply-templates select="docinfo/txt"/>
		</div>
	</xsl:template>
</xsl:stylesheet>
