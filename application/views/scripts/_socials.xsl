<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template name="socials">
        <div style="float:left; margin-top:20px;">
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru" data-hashtags="ssk">Twitt</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>


            <!-- Place this tag where you want the +1 button to render. -->
            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300"></div>

            <!-- Place this tag after the last +1 button tag. -->
            <script type="text/javascript">
                window.___gcfg = {lang: 'ru'};

                (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                })();
            </script>

            <div id="fb-root"></div>

            <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>

            <div class="fb-like" data-href="http://ssk.ua" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="light" data-layout="standard" data-action="like" data-show-faces="false" data-send="false"></div>

        </div>
    </xsl:template>

</xsl:stylesheet>