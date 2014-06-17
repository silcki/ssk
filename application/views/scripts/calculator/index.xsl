<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "../symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="../_main.xsl"/>

    <xsl:template match="calculator_tabs">
        <li>
            <xsl:choose>
                <xsl:when test="@active=1">
                    <xsl:attribute name="class">ui-state-default ui-corner-top ui-tabs-active ui-state-active</xsl:attribute>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:attribute name="class">ui-state-default ui-corner-top</xsl:attribute>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="image/@src">
                <xsl:attribute name="style">width:<xsl:value-of select="image/@w"/>px; height:<xsl:value-of select="image/@h"/>px;</xsl:attribute>
            </xsl:if>
            <a title="{name}">
                <xsl:choose>
                    <xsl:when test="position()=1">
                        <xsl:attribute name="href"><xsl:value-of select="//data/@self_url"/></xsl:attribute>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:attribute name="href"><xsl:value-of select="url"/></xsl:attribute>
                    </xsl:otherwise>
                </xsl:choose>
                <xsl:if test="image/@src">
                    <xsl:attribute name="style">display: block; background: url("<xsl:value-of select="image/@src"/>") no-repeat; width:<xsl:value-of select="image/@w"/>px; height:<xsl:value-of select="image/@h"/>px;</xsl:attribute>
                    <!-- text-indent:-9999px; overflow: hidden;-->
                </xsl:if>
                <xsl:value-of select="name"/>
            </a>
        </li>
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
            <div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                    <xsl:apply-templates select="calculator_tabs"/>
                </ul>

                <xsl:apply-templates select="calculator"/>
            </div>
		</div>
	</xsl:template>

</xsl:stylesheet>
