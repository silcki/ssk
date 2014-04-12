<?xml version="1.0" encoding="CP1251"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="page">
	<div id="video_content">
		<strong>Для проигрования данного ролика Вам необходимо скачать <a href="http://get.adobe.com/ru/flashplayer/">Flash Player.</a></strong>
	</div>
	<script type="text/javascript">
		var so = new SWFObject("/i/uppod.swf", "sotester", "500", "375", "9", "#ffffff");
		so.addParam("allowFullScreen", "true");
		so.addParam("allowScriptAccess", "always");
		so.addParam("wmode", "transparent");
		so.addParam("flashvars", "st=/css/video.txt&amp;file=/images/gallery_video/<xsl:value-of select="video_file"/>");
		so.write("video_content");		
	</script>
</xsl:template>

</xsl:stylesheet>
