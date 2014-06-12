<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "../symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="../_main.xsl"/>

    <xsl:template match="calculator" mode="tabs">
        <li><a href="#{indent}"><xsl:value-of select="name"/></a></li>
    </xsl:template>

    <xsl:template match="calculator">
        <div id="{indent}">
            <xsl:value-of select="txt" disable-output-escaping="yes"/>
        </div>
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
            <div id="tabs">
                <ul>
                    <xsl:apply-templates select="calculator" mode="tabs"/>
                </ul>

                <xsl:apply-templates select="calculator"/>
            </div>
		</div>
	</xsl:template>

</xsl:stylesheet>
