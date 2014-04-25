<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="../_main.xsl"/>

	<xsl:template match="work_rubrics">
		<xsl:choose>
			<xsl:when test="@sel=1"><li><span><xsl:value-of select="name" /></span></li></xsl:when>
			<xsl:otherwise>
				<li>
					<a>
					<xsl:attribute name="href">/announcement/all/rid/<xsl:value-of select="@id"/>/<xsl:if test="/page/data/@types_id &gt; 0">tid/<xsl:value-of select="/page/data/@types_id"/>/</xsl:if></xsl:attribute>
					<xsl:value-of select="name" /></a>
				</li>
				</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template match="work_types">
	<li>
	  <p>
		<xsl:choose>
			<xsl:when test="@sel=1"><span><xsl:value-of select="name" /></span></xsl:when>
			<xsl:otherwise><a href="/announcement/all/tid/{@id}/"><xsl:value-of select="name" /></a></xsl:otherwise>
		</xsl:choose>		
	  </p>	
	</li>	
	</xsl:template>
	
	<xsl:template match="rubrics">
		<option value="{@id}">
			<xsl:apply-templates select="name"/>
		</option>
	</xsl:template>
	
	<xsl:template match="types">
		<option value="{@id}">
			<xsl:apply-templates select="name"/>
		</option>
	</xsl:template>
	
	<xsl:template match="announcement">
		<div class="newsall">
			<div class="block news">
				<div class="def tr"></div>
				<div class="def tl"></div>
				<div class="blockcontent">
					<div class="head"><a href="/announcement/view/n/{@id}/" class="more">Подробнее</a>
						<h2><a href="/announcement/view/n/{@id}/"><xsl:value-of select="title" /></a></h2>
					</div>
					<div class="text">
						<p><xsl:apply-templates select="text" /></p>
						<dl>
							<xsl:if test="organization!=''">
								<dt><xsl:value-of select="/page/form_organization" />:</dt>
								<dd><xsl:value-of select="organization" /></dd>
							</xsl:if>
							<xsl:if test="country!=''">
								<dt><xsl:value-of select="/page/form_country" />:</dt>
								<dd><xsl:value-of select="country" /></dd>
							</xsl:if>
							<xsl:if test="city!=''">
								<dt><xsl:value-of select="/page/form_city" />:</dt>
								<dd><xsl:value-of select="city" /></dd>
							</xsl:if>
							<xsl:if test="name!=''">
								<dt><xsl:value-of select="/page/form_name" />:</dt>
								<dd><xsl:value-of select="name" /></dd>
							</xsl:if>
							<xsl:if test="phone!=''">
								<dt><xsl:value-of select="/page/form_phone" />:</dt>
								<dd><xsl:value-of select="phone" /></dd>
							</xsl:if>
							<xsl:if test="fax!=''">
								<dt><xsl:value-of select="/page/form_fax" />:</dt>
								<dd><xsl:value-of select="fax" /></dd>
							</xsl:if>
							<xsl:if test="email!=''">
								<dt><xsl:value-of select="/page/form_email" />:</dt>
								<dd><a href="mailto:{email}"><xsl:value-of select="email" /></a></dd>
							</xsl:if>							
							<dt><xsl:value-of select="/page/announcement_type" />:</dt>
							<dd><a href="/announcement/all/tid/{@types_id}/"><xsl:value-of select="at_name" /></a> | <a href="/announcement/all/rid/{@rubrics_id}/"><xsl:value-of select="ar_name" /></a></dd>
						</dl>
					</div>
				</div>
				<div class="def br"></div>
				<div class="def bl"></div>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template name="section_url">
		<xsl:apply-templates select="/page/lang_name"/>/announcement/all/<xsl:if test="/page/data/@rubrics_id &gt; 0">rid/<xsl:value-of select="/page/data/@rubrics_id"/></xsl:if><xsl:if test="/page/data/@types_id &gt; 0">tid/<xsl:value-of select="/page/data/@types_id"/></xsl:if>
	</xsl:template>	
	
<xsl:template name="section_first_url">
	<xsl:value-of select="//data/@file_name"/>
</xsl:template>	
		
<xsl:template match="data">
	<ul class="breadcrumbs">
		<li><a href="{/page/lang_name}/"><xsl:value-of select="/page/page_main" /></a></li>
		<!--<li><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></li>-->
	</ul>
	<h1 class="breadcrumbsh"><xsl:value-of select="/page/docinfo/name"  disable-output-escaping="yes"/></h1>
	<div class="kategory">
		<xsl:if test="count(work_types) &gt; 0">
			<h3><xsl:value-of select="/page/announcement_type" /></h3>
			<ul class="col">
				<xsl:if test="/page/data/@types_id &gt; 0"><li><a href="/announcement/all/"><xsl:value-of select="/page/system_all" /></a></li></xsl:if>
				<xsl:apply-templates select="work_types" />
			</ul>
		</xsl:if>
		<xsl:if test="count(work_rubrics) &gt; 0">
			<h3><xsl:value-of select="/page/announcement_rubrics" /></h3>
			<ul class="rubr2">
				<xsl:if test="/page/data/@rubrics_id &gt; 0">
					<li>
						<a>
							<xsl:attribute name="href">/announcement/all/<xsl:if test="/page/data/@types_id &gt; 0">tid/<xsl:value-of select="/page/data/@types_id"/>/</xsl:if></xsl:attribute>
							<xsl:value-of select="/page/system_all" />
						</a>
					</li>
				</xsl:if>				
				<xsl:apply-templates select="work_rubrics" />
			</ul>
		</xsl:if>
		<div class="faq">
			<div class="block">
				<div class="def tr"></div>
				<div class="def tl"></div>
				<div class="blockcontent">
					<div class="head">
						<h2><a href="#"><xsl:value-of select="/page/add_announcement" /></a></h2>
					</div>					
					<div class="text">
						<div class="zakazoformit">
							<form action="" id="ajaxlink" method="post">
								<div class="form2">
									<xsl:if test="count(/page/error_messages) &gt; 0">
										<ul>					
											<xsl:apply-templates select="/page/error_messages"/>
										</ul>
									</xsl:if>
									<p><xsl:value-of select="/page/banner_announcement_form/description" /></p>
									<div>
										<label for="organization"><xsl:value-of select="/page/form_organization" />:</label>
										<input type="text" id="organization" name="organization" value="{/page/organization_err}"/>
										<label for="country"><xsl:value-of select="/page/form_country" />:</label>
										<input type="text" id="country" name="country" value="{/page/country_err}"/>
									</div>
									<div>
										<label for="city"><xsl:value-of select="/page/form_city" />:</label>
										<input type="text" id="city" name="city" value="{/page/city_err}"/>
										<label for="title"><xsl:value-of select="/page/form_title" />:</label>
										<input type="text" id="title" name="title" value="{/page/title_err}"/>
									</div>
									<div>
										<label for="name"><xsl:value-of select="/page/form_name" />:</label>
										<input type="text" id="name" name="name" value="{/page/name_err}"/>
										<label for="email"><xsl:value-of select="/page/form_email" />:</label>
										<input type="text" id="email" name="email" value="{/page/email_err}"/>
									</div>
									<div>
										<label for="phone"><xsl:value-of select="/page/form_phone" />:</label>
										<input type="text" id="phone" name="phone" value="{/page/phone_err}"/>
										<label for="fax"><xsl:value-of select="/page/form_fax" /></label>
										<input type="text" id="fax" name="fax" value="{/page/fax_err}" />
									</div>
									<div>
										<label for="rubrics"><xsl:value-of select="/page/announcement_rubrics" /></label>
										<select name="announcement_rubrics_id">
											<xsl:apply-templates select="rubrics" />
										</select>
										<label for="types"><xsl:value-of select="/page/announcement_type" /></label>
										<select name="announcement_types_id">
											<xsl:apply-templates select="types" />
										</select>
									</div>
									<div>
										<label for=""><xsl:value-of select="/page/form_ann_text" /></label>
										<textarea cols="50" rows="5" name="text"></textarea>
									</div>
									<div class="capcha">
										<label for="capcha"><xsl:value-of select="/page/form_captcha" />t:</label>
										<span id="dle-captcha"><span id="dlecaptcha"><script>reload();</script></span><a href="javascript:void();" onclick="reload(); return false;"><xsl:value-of select="/page/form_refresh" /></a></span>
										<div class="errholder">
											<input type="text" id="capcha" name="capcha"/>
											<span id="err"></span> </div>
										<a class="zakazat" id="zakaz" href="javascript:void(0);" onclick="nnouncement_frm()">Отправить</a> </div>
								</div>
							</form>						
						</div>
					</div>				
				</div>
				<div class="def br"></div>
				<div class="def bl"></div>
			</div>
		</div>		
	</div>
	<xsl:if test="count(/page/error_messages) &gt; 0">
		<script>
			$(document).ready(function(){           
				$(".zakazoformit").slideDown();
				$(".zakaz .br").hide();
				$(".zakaz .bl").hide();
				$(this).addClass("open");
			});
		</script>
	</xsl:if>
	<xsl:apply-templates select="announcement" />
	<ul class="pagenav">
		<xsl:apply-templates select="/page/data/section"/>
	</ul>
</xsl:template>

</xsl:stylesheet>