<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:import href="_base.xsl"/>
	<!-- HEAD -->
	<xsl:template name="title">
		<xsl:choose>
			<xsl:when test="//doc_meta/title!=''">
				<xsl:apply-templates select="//doc_meta/title"/>
			</xsl:when>
			<xsl:otherwise>СКЛАД СЕРВИС</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<!-- Keywords -->
	<xsl:template name="keywords">
		<xsl:variable name="keywords">
			<xsl:choose>
				<xsl:when test="//doc_meta/keywords!=''">
					<xsl:apply-templates select="//doc_meta/keywords"/>
				</xsl:when>
				<xsl:otherwise>СКЛАД СЕРВИС</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:call-template name="keyword">
			<xsl:with-param name="key">
				<xsl:value-of select="$keywords"/>
			</xsl:with-param>
		</xsl:call-template>
	</xsl:template>
	<xsl:template name="keyword">
		<xsl:param name="key"/>
		<xsl:variable name="key1"><![CDATA[<meta name="keywords" content="]]></xsl:variable>
		<xsl:variable name="key2"><![CDATA["/>]]></xsl:variable>
		<xsl:value-of select="$key1" disable-output-escaping="yes"/>
		<xsl:value-of select="$key" disable-output-escaping="yes"/>
		<xsl:value-of select="$key2" disable-output-escaping="yes"/>
	</xsl:template>
	<!-- Keywords -->
	<!-- Description -->
	<xsl:template name="description">
		<xsl:variable name="description">
			<xsl:choose>
				<xsl:when test="//doc_meta/description!=''">
					<xsl:apply-templates select="//doc_meta/description"/>
				</xsl:when>
				<xsl:otherwise>СКЛАД СЕРВИС</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:call-template name="descript">
			<xsl:with-param name="des">
				<xsl:value-of select="$description"/>
			</xsl:with-param>
		</xsl:call-template>
	</xsl:template>

	<xsl:template name="descript">
		<xsl:param name="des"/>
		<xsl:variable name="des1"><![CDATA[<meta name="description" content="]]></xsl:variable>
		<xsl:variable name="des2"><![CDATA["/>]]></xsl:variable>
		<xsl:value-of select="$des1" disable-output-escaping="yes"/>
		<xsl:value-of select="$des" disable-output-escaping="yes"/>
		<xsl:value-of select="$des2" disable-output-escaping="yes"/>
	</xsl:template>
	<!-- Description -->
	<xsl:template name="headSection">
		<title>
			<xsl:call-template name="title"/>
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<xsl:call-template name="keywords"/>
		<xsl:call-template name="description"/>
	</xsl:template>
	<!-- Catalog -->
	<xsl:template match="cattree" mode="sub">
		<xsl:variable name="cid">
			<xsl:value-of select="@catalogue_id"/>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="@catalogue_id=/page/data/@id">
				<li>
					<span class="active">
						<xsl:value-of select="name"/>
					</span>
				</li>
			</xsl:when>
			<xsl:when test="$cid=/page/data/item/@catalogue_id">
				<li>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</li>
			</xsl:when>
			<xsl:otherwise>
				<li>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</li>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="cattree">
		<xsl:variable name="cid">
			<xsl:value-of select="@catalogue_id"/>
		</xsl:variable>
		<div>
			<xsl:if test="@on_path=1">
				<xsl:attribute name="id">current</xsl:attribute>
			</xsl:if>
			<h2>— <a href="{url}">
					<xsl:value-of select="name"/>
				</a>
			</h2>
			<xsl:if test="count(cattree[@parent_id=$cid and @in_menu=1]) &gt; 0">
				<ul>
					<xsl:apply-templates select="cattree[@parent_id=$cid and @in_menu=1]" mode="sub"/>
				</ul>
			</xsl:if>
		</div>
	</xsl:template>
	<xsl:template match="cattree">
		<xsl:variable name="cid">
			<xsl:value-of select="@catalogue_id"/>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="@catalogue_id=/page/data/@id">
				<li>
					<span class="active">
						<xsl:value-of select="name"/>
					</span>
				</li>
			</xsl:when>
			<xsl:when test="$cid=/page/data/item/@catalogue_id">
				<li>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</li>
			</xsl:when>
			<xsl:otherwise>
				<li>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</li>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="items" mode="left">
		<li>
			<xsl:choose>
				<xsl:when test="//data/@item_id = @item_id">
					<xsl:attribute name="class">active</xsl:attribute>
					<a href="{url}">
						<xsl:attribute name="class">active</xsl:attribute>
						<xsl:value-of select="name"/>
					</a>
				</xsl:when>
				<xsl:otherwise>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</xsl:otherwise>
			</xsl:choose>
		</li>
	</xsl:template>
	<xsl:template match="cattree" mode="left">
		<xsl:param name="level"/>
		<xsl:variable name="cid">
			<xsl:value-of select="@catalogue_id"/>
		</xsl:variable>
		<li>
			<xsl:choose>
				<xsl:when test="@on_path=1">
					<xsl:attribute name="class">active</xsl:attribute>
					<a href="{url}">
						<xsl:attribute name="class">active</xsl:attribute>
						<!--<xsl:choose>
							<xsl:when test="@item_count &gt; 1 and count(//data/item) &gt; 0"><xsl:attribute name="href"><xsl:value-of select="url"/></xsl:attribute></xsl:when>
							<xsl:when test="count(cattree[@parent_id=$cid and @in_menu=1]) &gt; 0"><xsl:attribute name="href"><xsl:value-of select="url"/></xsl:attribute></xsl:when>
						</xsl:choose>-->
						<xsl:value-of select="name"/>
					</a>
				</xsl:when>
				<xsl:otherwise>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:if test="count(cattree[@parent_id=$cid and @in_menu=1]) &gt; 0">
				<ul>
					<xsl:if test="$level = 1">
						<xsl:attribute name="style">margin:0;</xsl:attribute>
					</xsl:if>
					<xsl:apply-templates select="cattree[@parent_id=$cid and @in_menu=1]" mode="left">
						<xsl:with-param name="level">0</xsl:with-param>
					</xsl:apply-templates>
				</ul>
			</xsl:if>
			<xsl:if test="count(itemnode/items) &gt; 0">
				<ul>
					<xsl:apply-templates select="itemnode/items" mode="left"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!-- Catalog -->
	<!-- Top menu -->
	<xsl:template match="main_menu">
		<xsl:variable name="pid">
			<xsl:apply-templates select="@another_pages_id"/>
		</xsl:variable>
		<xsl:variable name="pos">
			<xsl:value-of select="position()"/>
		</xsl:variable>
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="@is_new_win='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li>
			<a>
				<xsl:if test="@is_node=0 or not(@is_node)">
					<xsl:attribute name="href"><xsl:value-of select="url"/></xsl:attribute>
				</xsl:if>
				<xsl:choose>
					<xsl:when test="@via_js=1">
						<xsl:attribute name="vlink"><xsl:value-of select="url"/></xsl:attribute>
						<xsl:attribute name="href">#</xsl:attribute>
					</xsl:when>
					<xsl:when test="@is_node=1">
						<xsl:attribute name="style">cursor: default;</xsl:attribute>
					</xsl:when>					
				</xsl:choose>
				<xsl:if test="@on_path=1 or count(main_menu[@on_path=1])">
					<xsl:attribute name="class">active</xsl:attribute>
				</xsl:if>
				<xsl:value-of select="name"/>
			</a>
			<xsl:if test="count(main_menu) &gt; 0">
				<ul>
					<xsl:apply-templates select="main_menu"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<xsl:template match="main_menu" mode="left">
		<xsl:param name="level"/>
		<xsl:variable name="pid">
			<xsl:apply-templates select="@another_pages_id"/>
		</xsl:variable>
		<xsl:variable name="pos">
			<xsl:value-of select="position()"/>
		</xsl:variable>
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="@is_new_win='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li>
			<xsl:choose>
				<xsl:when test="count(main_menu[@parent_id=$pid]) &gt; 0 or @on_path=1">
					<xsl:attribute name="class">active</xsl:attribute>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
					<xsl:if test="count(main_menu[@parent_id=$pid]) &gt; 0">
						<ul>
							<xsl:if test="$level = 1">
								<xsl:attribute name="style">margin:0;</xsl:attribute>
							</xsl:if>
							<xsl:apply-templates select="main_menu[@parent_id=$pid]" mode="left">
								<xsl:with-param name="level">0</xsl:with-param>
							</xsl:apply-templates>
						</ul>
					</xsl:if>
				</xsl:when>
				<xsl:otherwise>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</xsl:otherwise>
			</xsl:choose>
		</li>
	</xsl:template>
	<!-- Top menu -->
	<xsl:template match="gallery_tree" mode="left">
		<xsl:param name="level"/>
		<xsl:variable name="cid">
			<xsl:value-of select="@id"/>
		</xsl:variable>
		<li>
			<xsl:if test="@on_path=1">
				<xsl:attribute name="class">active</xsl:attribute>
			</xsl:if>
			<a href="{url}">
				<xsl:if test="@on_path=1">
					<xsl:attribute name="class">active</xsl:attribute>
				</xsl:if>
				<xsl:value-of select="name"/>
			</a>
			<xsl:if test="count(gallery_tree[@parent_id=$cid]) &gt; 0">
				<ul>
					<xsl:if test="$level = 1">
						<xsl:attribute name="style">margin:0;</xsl:attribute>
					</xsl:if>
					<xsl:apply-templates select="gallery_tree[@parent_id=$cid]" mode="left">
						<xsl:with-param name="level">0</xsl:with-param>
					</xsl:apply-templates>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
	<!--Банера-->
	<xsl:template match="banner_header_page">
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="newwin='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="type='0'">
				<!-- Картинка -->
				<xsl:choose>
					<xsl:when test="burl!=''">
						<a href="{burl}" target="{$target}">
							<img src="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}" alt=""/>
						</a>
					</xsl:when>
					<xsl:otherwise>
						<img src="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}" alt=""/>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Картинка -->
			</xsl:when>
			<xsl:when test="type='2'">
				<!-- Текст -->
				<p>
					<xsl:value-of select="description"/>
				</p>
				<!-- Текст -->
			</xsl:when>
			<xsl:otherwise>
				<!-- Флеш -->
				<xsl:choose>
					<xsl:when test="burl!=''">
						<div style="z-index:1">
							<a href="{burl}" target="{$target}">
								<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
									<param name="movie" value="/images/bn/{image/@src}"/>
									<param value="transparent" name="wmode"/>
								</object>
							</a>
						</div>
					</xsl:when>
					<xsl:otherwise>
						<div style="z-index:1">
							<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
								<param name="movie" value="/images/bn/{image/@src}"/>
								<param value="transparent" name="wmode"/>
							</object>
						</div>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Флеш -->
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="banner_footer_page">
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="newwin='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:choose>
			<xsl:when test="type='0'">
				<!-- Картинка -->
				<xsl:choose>
					<xsl:when test="burl!=''">
						<a href="{burl}" target="{$target}">
							<img src="/images/bn/{image/@src}" alt=""/>
						</a>
					</xsl:when>
					<xsl:otherwise>
						<img src="/images/bn/{image/@src}" alt=""/>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Картинка -->
			</xsl:when>
			<xsl:when test="type='2'">
				<!-- Текст -->
				<p>
					<xsl:value-of select="description"/>
				</p>
				<!-- Текст -->
			</xsl:when>
			<xsl:otherwise>
				<!-- Флеш -->
				<xsl:choose>
					<xsl:when test="burl!=''">
						<div style="z-index:1">
							<a href="{burl}" target="{$target}">
								<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
									<param name="movie" value="/images/bn/{image/@src}"/>
									<param value="transparent" name="wmode"/>
								</object>
							</a>
						</div>
					</xsl:when>
					<xsl:otherwise>
						<div style="z-index:1">
							<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
								<param name="movie" value="/images/bn/{image/@src}"/>
								<param value="transparent" name="wmode"/>
							</object>
						</div>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Флеш -->
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="banner_left_page|banner_left_side_menu|banner_left_side">
		<xsl:variable name="target">
			<xsl:choose>
				<xsl:when test="newwin='1'">_blank</xsl:when>
				<xsl:otherwise>_self</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<div>
			<xsl:if test="position() = last() and position() != 1">
				<xsl:attribute name="style">margin-top:30px;</xsl:attribute>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="type='0'">
					<!-- Картинка -->
					<xsl:choose>
						<xsl:when test="burl!=''">
							<a href="{burl}" target="{$target}">
								<img src="/images/bn/{image/@src}" alt=""/>
							</a>
						</xsl:when>
						<xsl:otherwise>
							<img src="/images/bn/{image/@src}" alt=""/>
						</xsl:otherwise>
					</xsl:choose>
					<!-- Картинка -->
				</xsl:when>
				<xsl:when test="type='2'">
					<!-- Текст -->
					<p>
						<xsl:apply-templates select="description"/>
					</p>
					<!-- Текст -->
				</xsl:when>
				<xsl:otherwise>
					<!-- Флеш -->
					<xsl:choose>
						<xsl:when test="burl!=''">
							<div style="z-index:1">
								<a href="{burl}" target="{$target}">
									<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
										<param name="movie" value="/images/bn/{image/@src}"/>
										<param value="transparent" name="wmode"/>
									</object>
								</a>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div style="z-index:1">
								<object type="application/x-shockwave-flash" data="/images/bn/{image/@src}" width="{image/@w}" height="{image/@h}">
									<param name="movie" value="/images/bn/{image/@src}"/>
									<param value="transparent" name="wmode"/>
								</object>
							</div>
						</xsl:otherwise>
					</xsl:choose>
					<!-- Флеш -->
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:template>
	<!--Банера-->
	<!--Опрос-->
	<xsl:template match="otvets">
		<xsl:choose>
			<xsl:when test="/page/site_vote &gt; 0">
				<li>
					<strong>
						<xsl:value-of select="name"/>&#160;</strong>
					<div class="diagram">
						<span style="width: {@percent}%;" class="image">диаграма</span>
						<span class="text">
							<xsl:value-of select="@percent"/>%</span>
					</div>
				</li>
			</xsl:when>
			<xsl:otherwise>
				<li>
					<xsl:choose>
						<xsl:when test="position()=1">
							<input type="radio" name="opr" id="opr{@id}" value="{@id}" checked="checked"/>
						</xsl:when>
						<xsl:otherwise>
							<input type="radio" name="opr" id="opr{@id}" value="{@id}"/>
						</xsl:otherwise>
					</xsl:choose>
					<label for="opr{@id}">
						<xsl:value-of select="name"/>
					</label>
				</li>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="vopros">
		<div>
			<xsl:choose>
				<xsl:when test="/page/site_vote &gt; 0">
					<xsl:attribute name="class">vote result</xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="class">vote</xsl:attribute>
				</xsl:otherwise>
			</xsl:choose>
			<div class="box">
				<h2>
					<xsl:value-of select="name"/>
				</h2>
				<form action="/index/vote/" method="post" id="vote">
					<ul class="vote-list">
						<xsl:apply-templates select="otvets"/>
					</ul>
					<xsl:choose>
						<xsl:when test="/page/site_vote &gt; 0">
							<a href="{/page/lang_name}/index/vote/" class="btn_send">Просмотреть все</a>
						</xsl:when>
						<xsl:otherwise>
							<a href="#" id="sendvote" class="btn_send">Отправить</a>
						</xsl:otherwise>
					</xsl:choose>
				</form>
			</div>
		</div>
	</xsl:template>
	<!--Опрос-->
	<!--Языки-->
	<xsl:template match="langs">
		<xsl:choose>
			<xsl:when test="system_name=/page/lang">
				<li class="active">
					<xsl:value-of select="name"/>
				</li>
			</xsl:when>
			<xsl:otherwise>
				<li>
					<a href="{url}">
						<xsl:value-of select="name"/>
					</a>
				</li>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<!--Языки-->
	<xsl:template match="breadcrumbs">
		<xsl:choose>
            <xsl:when test="position()=last() and position()=1"><li><a href="{url}"><xsl:value-of select="name"/></a></li></xsl:when>
			<xsl:when test="position()!=last()"><li><a href="{url}"><xsl:value-of select="name"/></a></li></xsl:when>
			<!--<xsl:otherwise><li><xsl:value-of select="name"/></li></xsl:otherwise>-->
		</xsl:choose>
	</xsl:template>
	<xsl:template match="error_messages">
		<li>
			<xsl:value-of select="err_mess"/>
		</li>
	</xsl:template>
	<xsl:template match="banner_java_scripts">
		<xsl:apply-templates select="banner_code"/>
	</xsl:template>
	<xsl:template name="javaScript"/>
	<!--<xsl:template match="/page/text_zakaz_phone">
		<xsl:apply-templates select="banner_code"/>
</xsl:template>-->
	<xsl:template match="/page">
		<xsl:variable name="doctype"><![CDATA[<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">]]></xsl:variable>
		<!--<xsl:value-of select="$doctype" disable-output-escaping="yes"/>-->
		<html>
			<head>
				<xsl:call-template name="headSection"/>
				<xsl:variable name="style"><![CDATA[<!--[if IE]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"/><![endif]-->]]></xsl:variable>
				<!--<link rel="stylesheet" type="text/css" href="/css/fancybox.css" media="screen"/>
-->
				<link rel="stylesheet" type="text/css" href="/css/print.css" media="print"/>
				<xsl:value-of select="$style" disable-output-escaping="yes"/>
				<script>
					var callback_mess = '<xsl:value-of select="/page/text_callback_ticket"/>';
					var complain_mess = '<xsl:value-of select="/page/text_complain_ticket"/>';
				</script>
				<!--<script type='text/javascript' src='/js/jquery.js'></script>
				<script type='text/javascript' src='/js/fancybox.js'></script>
				<script type='text/javascript' src='/js/scrollto.js'></script>-->
				<script type="text/javascript" src="/js/lib/jQuery/jquery-1.8.0.min.js"/>
				<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js" type="text/javascript"></script>
				<script type="text/javascript" src="/js/jquery.form.js"/>
				<!-- Add mousewheel plugin (this is optional) -->
				<!--	<script type="text/javascript" src="/js/lib/jQuery/jquery.mousewheel-3.0.6.pack.js"></script>-->
				<!-- Add fancyBox main JS and CSS files -->
				<script type="text/javascript" src="/js/lib/FancyBox/jquery.fancybox.pack.js?v=2.0.6"/>
				<link rel="stylesheet" type="text/css" href="/js/lib/FancyBox/jquery.fancybox.css?v=2.0.6" media="screen"/>
				<!-- Add Button helper (this is optional) -->
				<!--	<link rel="stylesheet" type="text/css" href="/js/lib/FancyBox/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="/js/lib/FancyBox/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>-->
				<!-- Add Thumbnail helper (this is optional) -->
				<!--	<link rel="stylesheet" type="text/css" href="/js/lib/FancyBox/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="/js/lib/FancyBox/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>		-->
				<!-- Add Media helper (this is optional) -->
				<script type="text/javascript" src="/js/lib/FancyBox/helpers/jquery.fancybox-media.js?v=1.0.0"/>
				<link rel="stylesheet" type="text/css" href="/css/all.css" media="screen"/>
				<script type="text/javascript" src="/js/main.js"/>
				<script type="text/javascript" src="/js/scripts.js"/>
				<script type="text/javascript" src="/sokoban/sokoban_ssk.js"/>
				<script type="text/javascript" src="/js/swfobject.js"/>
				<script type="text/javascript" src="/js/jquery.cookie.js"/>
				<link id="favicon" href="/favicon.ico" rel="icon" type="image/x-icon"/>
				<xsl:apply-templates select="banner_java_scripts"/>
				<xsl:call-template name="javaScript"/>
				
				<xsl:if test="data/section/@rel_prev !='' ">
					<link rel="prev" href="{data/section/@rel_prev}"/>
				</xsl:if>
				<xsl:if test="data/section/@rel_next !='' ">
					<link rel="next" href="{data/section/@rel_next}"/>
				</xsl:if>

                <link rel="stylesheet" type="text/css" href="/css/client_filter.css" media="screen"/>
                <script type="text/javascript" src="/js/client_filter.js"/>

                <meta name="google-site-verification" content="Zh9o5kDmgl9hJGCJcNb8ngidmwpUk-RbZOq88dR_6gk" />
			</head>
			<body>


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter22337347 = new Ya.Metrika({id:22337347,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    ut:"noindex"});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/22337347?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

				<div id="main">
					<xsl:if test="//data/@is_start=1">
						<xsl:attribute name="class">main_index</xsl:attribute>
					</xsl:if>
					<!--<xsl:if test="//data/@is_start=1"><div class="bgtop"></div></xsl:if>-->
					<div class="bgtop"/>
					<div id="header">
						<div class="holder">
							<p class="logo">
								<a href="http://ssk.ua/" style="text-decoration: none;">
									<!--<xsl:if test="not(//data/@is_start)"> -->
									<!--	<xsl:attribute name="href"><xsl:value-of select="/page/lang_name"/>/</xsl:attribute>-->
									<!--</xsl:if> -->
									<img src="/i/logo.png" alt="" height="65" width="168"/><img style="margin:0px 142px 0px 27px;" src="/i/slogan.png" alt="" height="65" width="257"/>
								</a>
							</p>
							<div class="phone_hold">
								<!--<p class="phone"><xsl:apply-templates select="/page/text_zakaz_phone"/></p>-->
								<p class="phone">
									<xsl:apply-templates select="/page/banner_header_phone1/description"/>
								</p>
								<p class="call_holder">
									<xsl:value-of select="/page/text_zakaz_callback"/>&#160;<a href="/ajax/callback/" class="callback fancybox.ajax" id="callback">
										<xsl:value-of select="/page/text_callback_callback"/>
									</a>
								</p>
							</div>
							<!--  Пока удаляем поиск -->
							<!--<xsl:if test="not(//data/@is_start)">
								<form class="search" action="/search/all/" method="get">
									<fieldset>
										<input type="text" value="{search_text}" id="search" name="q" class="text">
											<xsl:if test="data/query!=''"><xsl:attribute name="value"><xsl:value-of select="data/query"/></xsl:attribute> </xsl:if>
										</input>
										<input type="image" src="/i/search.png" />
									</fieldset>
								</form>
							</xsl:if>-->
							<xsl:if test="count(langs) &gt; 1">
								<ul class="lang">
									<xsl:apply-templates select="langs"/>
								</ul>
							</xsl:if>
						</div>
					</div>
					<div class="topmenu">
						<xsl:if test="not(//data/@is_start)">
							<xsl:attribute name="class">topmenu topmenu_inner</xsl:attribute>
						</xsl:if>
						<div class="holder">
							<ul>
								<xsl:apply-templates select="main_menu"/>
							</ul>
						</div>
					</div>
					<xsl:apply-templates select="data" mode="body"/>
					<div id="footer">
						<!--<xsl:if test="not(//data/@is_start)"><xsl:attribute name="id">mainfooter</xsl:attribute></xsl:if>-->
						<!--<xsl:attribute name="id">mainfooter</xsl:attribute>-->
						<!--<div id="footer">-->
						<div class="left">
							<p class="copyright">
								<xsl:apply-templates select="banner_footer_copy/description"/>
							</p>
							<address>
								<xsl:apply-templates select="banner_footer_address/description"/>
							</address>
						</div>
						<div class="rightBlock">
							<div class="counter">
								
								
								<xsl:value-of select="$ya_metrics" disable-output-escaping="yes"/>
								<!--<div><xsl:value-of select="$google_analytics" disable-output-escaping="yes"/></div>-->
							</div>
                                                        <br/>
							<div class="right">
								<a target="_blank" href="http://vk.com/ssk_ua">
								<img src="/images/social/vk.png" />
								</a>

								<a target="_blank" href="https://www.facebook.com/pages/%D0%A1%D0%BA%D0%BB%D0%B0%D0%B4-%D0%A1%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D0%9A%D0%B8%D0%B5%D0%B2/594545797251238">
								<img src="/images/social/fb.png" />
								</a>

								<a target="_blank" href="https://plus.google.com/communities/106133885575830478104">
								<img src="/images/social/gplus.png" />
								</a>

								<a target="_blank" href="http://www.youtube.com/user/SkladServiceKiev?feature=watch">
								<img src="/images/social/youtube.png" />
								</a>                                                           
							</div>
						</div>
					</div>
					<!--</div>-->
					<!--<xsl:if test="//data/@is_start=1"><div class="bgbottom"></div></xsl:if>-->
					<div class="bgbottom"/>
				</div>


<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 981071541;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/981071541/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

                                <!-- BEGIN JIVOSITE CODE {literal} -->
                                <script type='text/javascript'>
                                  (function(){
                                    var widget_id = '92113';
                                    var s = document.createElement('script');
                                    s.type = 'text/javascript';
                                    s.async = true;
                                    s.src = '//code.jivosite.com/script/widget/'+widget_id;
                                    var ss = document.getElementsByTagName('script')[0];
                                    ss.parentNode.insertBefore(s, ss);})();
                               </script>
                               <!-- {/literal} END JIVOSITE CODE -->
					<script type="text/javascript">
						$.cookie.path = '/';
						var referer_phone = $.cookie('referer_phone');
						if (typeof referer_phone != 'undefined') {						
							$('.phone_hold p.phone span').html(referer_phone);
						}
					</script>
			</body>
		</html>
	</xsl:template>
	<xsl:template match="txt//form">
		<div class="callback question">
			<div class="heading">
				<p>
					<a href="#">
						<xsl:value-of select="."/>
					</a>
				</p>
			</div>
			<p>
				<xsl:value-of select="/page/banner_feedback_form/description"/>
			</p>
			<xsl:call-template name="formFeedback">
				<xsl:with-param name="url">/ajax/sendfeedback/</xsl:with-param>
			</xsl:call-template>
		</div>
	</xsl:template>
	<xsl:template match="data[@is_start]" mode="body">
		<xsl:if test="count(headers) &gt; 0">
			<div class="gallary_hold">
				<div class="holder">
					<ul class="number">
						<xsl:apply-templates select="headers" mode="pos"/>
					</ul>
					<ul id="gallary">
						<xsl:apply-templates select="headers"/>
					</ul>
				</div>
			</div>
		</xsl:if>
		<div id="content">
			<div class="rubrholder">
				<div class="holder">
					<xsl:apply-templates select="/page/cattree[@in_main=1]"/>
				</div>
			</div>
			<xsl:apply-templates select="."/>
		</div>
	</xsl:template>
	
	<xsl:template match="left_banner" mode="pos">
	<li><a href="#"><xsl:value-of select="position()"/></a></li>
</xsl:template>

<xsl:template match="left_banner">
	<li>
		<table>
			<tr>
				<td style="background: none;">
					<div class="gal_img">
						<xsl:choose>
							<xsl:when test="url !='' ">
								<a href="{url}"><img src="/images/left_banns/{image/@src}" alt="" width="{image/@w}" height="{image/@h}" /></a>
							</xsl:when>
							<xsl:otherwise><img src="/images/left_banns/{image/@src}" alt="" width="{image/@w}" height="{image/@h}" /></xsl:otherwise>
						</xsl:choose>
					</div>
				</td>
			</tr>
			<tr>
				<td style="background: none;">
					<xsl:choose>
						<xsl:when test="image_alt_text/@src!=''">
						
							<xsl:choose>
								<xsl:when test="url !='' ">
									<a href="{url}"><img src="/images/left_banns/{image_alt_text/@src}" alt="" width="{image_alt_text/@w}" height="{image_alt_text/@h}" /></a>
								</xsl:when>
								<xsl:otherwise><img src="/images/left_banns/{image_alt_text/@src}" alt="" width="{image_alt_text/@w}" height="{image_alt_text/@h}" /></xsl:otherwise>
							</xsl:choose>
							
						</xsl:when>
						<xsl:otherwise><xsl:apply-templates select="description"/></xsl:otherwise>
					</xsl:choose>
				</td>
			</tr>
		</table>
	</li>
</xsl:template>

<xsl:template match="sokoban_levels">
    <option value="{level}"><xsl:value-of select="level"/></option>
</xsl:template>
	
	<xsl:template match="data[not(@is_start)]" mode="body">
		<xsl:variable name="menu_paretn_id">
			<xsl:choose>
				<xsl:when test="count(//main_menu[@parent_id = //data/@ap_id]) &gt; 0">
					<xsl:value-of select="//data/@ap_id"/>
				</xsl:when>
				<xsl:when test="count(//main_menu[@on_path=1 and @level &gt; 1]) &gt; 0">
					<xsl:value-of select="//main_menu[@on_path=1 and @level &gt; 1]/@parent_id"/>
				</xsl:when>
				<xsl:otherwise>-1</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<div class="holder">
			<div id="column">
				<xsl:if test="count(//banner_left_side_menu) &gt; 0">
					<div class="_text">
						<xsl:apply-templates select="//banner_left_side_menu"/>
					</div>
				</xsl:if>
				<xsl:if test="count(//main_menu[@parent_id = $menu_paretn_id]) &gt; 0 and count(gallery_tree) = 0">
					<ul class="menucat">
						<xsl:apply-templates select="//main_menu[@parent_id = $menu_paretn_id]" mode="left">
							<xsl:with-param name="level">1</xsl:with-param>
						</xsl:apply-templates>
					</ul>
				</xsl:if>
				<xsl:if test="@cat_id">
					<!--<p class="head"><xsl:value-of select="/page/item_catalog"/></p>-->
					<ul class="menucat">
						<xsl:apply-templates select="/page/cattree" mode="left">
							<xsl:with-param name="level">1</xsl:with-param>
						</xsl:apply-templates>
					</ul>
				</xsl:if>

				<xsl:if test="count(gallery_tree) &gt; 0">
					<ul class="menucat">
						<xsl:apply-templates select="//data/gallery_tree" mode="left">
							<xsl:with-param name="level">1</xsl:with-param>
						</xsl:apply-templates>
					</ul>
				</xsl:if>

				<xsl:if test="count(//page/left_banner) &gt; 0">
					<div class="gallary_hold" style="height: 370px; margin: 10px 6px;">
						<div class="holder">
							<ul class="number">
								<xsl:apply-templates select="//page/left_banner" mode="pos"/>							
							</ul>
							<ul id="gallary">
								<xsl:apply-templates select="//page/left_banner"/>							
							</ul>
						</div>
					</div>
				</xsl:if>

				
				<xsl:if test="count(//banner_left_side) &gt; 0">
					<div class="adv">
						<xsl:apply-templates select="//banner_left_side"/>
					</div>
				</xsl:if>
				<xsl:if test="count(//catalog_article) &gt; 0">
					<p class="head">Статьи по теме</p>
					<div class="newses">
						<xsl:apply-templates select="//catalog_article"/>
						<p class="allnews">
							<a href="{/page/lang_name}/articles/">
								<xsl:value-of select="/page/all_articles"/>
							</a>
						</p>
					</div>
				</xsl:if>
				<xsl:apply-templates select="//vopros"/>

				<div id="sokoban-ssk" style="margin-left:5px; margin-top:45px;">
					<img src="/sokoban/sokoban.jpg" style="margin-bottom:-10px;" />
<!--					<div class="sokoban_moves">Ходов сделано:&#160;<span></span></div>
					<div class="sokoban_total">Коробок:&#160;<span></span></div>
					<div class="sokoban_target">Коробок на месте:&#160;<span></span></div>-->
					<div id="sokoban_field_map"></div>
					<script type="text/javascript">
							document.onkeydown = detect_key;
							load_level(1);							
					</script>
<div class="sokoban_moves">Ходов сделано:&#160;<span></span></div>
<div align="right" style="float: right; margin-bottom: 0px;width: 100px;">
                        Уровни&#160;
                        <select name="sokoban_levels">
                            <xsl:apply-templates select="/page/sokoban_levels"/>
                        </select>
                    </div>
<div style="clear:both;"></div>
<div align="left" style="margin-bottom:25px;"><a href="#sokoban_reset" class="sokoban_reset" data-level="1">Повтор уровня</a></div>

					<img src="/sokoban/rules.jpg"/>
				</div>
				
			</div>
			<div class="content_holder">
				<div id="content">
<!-- closed by Boris 2014_01_31 begin -->
                                        <xsl:if test="//sectioninfo/image/@src!='' and 0=1">
						<img src="{//sectioninfo/image/@src}" alt="{//data/docinfo/name}"/>
					</xsl:if>
<!-- closed by Boris 2014_01_31 end -->
					<div class="breadcrumbs">
						<xsl:if test="count(//data/breadcrumbs) &gt; 0">
							<!--<div class="holder">-->
							<ul>
								<xsl:apply-templates select="//data/breadcrumbs"/>
							</ul>
							<!--</div>-->
						</xsl:if>
					</div>
					<xsl:apply-templates select="."/>
				</div>
			</div>

            <div id="sokoban_reset" style="display: none; height: 70px;">
                <table>
                    <tr>
                        <td colspan="2">Вы действительно хотите начать уровень заново?</td>
                    </tr>
                    <tr>
                        <td><a href="#" class="btn_send sokoban_reset_ok">Да</a></td>
                        <td width="50"><a href="#" class="btn_send sokoban_reset_cancel">Нет</a></td>
                    </tr>
                </table>
            </div>
		</div>
	</xsl:template>
</xsl:stylesheet>
