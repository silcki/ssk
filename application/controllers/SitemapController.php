<?php

class SitemapController extends CommonBaseController
{

    public $doc_id;

    function init()
    {
        parent::init();

        $this->getSysText('sitemap_another_pages');
        $this->getSysText('sitemap_news');
        $this->getSysText('sitemap_articles');
        $this->getSysText('sitemap_gallary');
    }

    public function indexAction()
    {

        $ap_id = $this->AnotherPages->getPageId('/sitemap/');

        $this->getDocMeta($ap_id);

        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);
        $this->getDocPath($ap_id);

        $this->getGalleryGroup(0, 1);
        $this->getVideoGalleryGroup(0, 1);
        $this->getNews();
        $this->getArticles();
    }

    public function getDocMeta($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                     $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                    $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }
    }

    private function getGalleryGroup($pid, $level)
    {
        $gallery = $this->Gallery->getGalleryGroup($pid, $this->lang_id);
        if ($gallery) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($gallery as $view) {
                $this->domXml->create_element('gallery_group', '', 2);
                $this->domXml->set_attribute(array('gallery_group_id' => $view['GALLERY_GROUP_ID']
                    , 'level' => $level
                ));

                $subs_count = $this->Gallery->getSubGroupGallery($view['GALLERY_GROUP_ID']);

                $href = '';
                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $view['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $view['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

//          if(!empty($_href) && $is_lang) $href = $lang.$_href;
//          elseif(!empty($_href) && !$is_lang) $href = $_href;

                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $gallery_chiled = $this->Gallery->getGalleryGroup($view['GALLERY_GROUP_ID'],
                                                                  $this->lang_id);
                if (!empty($gallery_chiled)) {
                    $level++;
                    $this->getGalleryGroup($view['GALLERY_GROUP_ID'], $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getVideoGalleryGroup($pid, $level)
    {
        $gallery = $this->Gallery->getGalleryVideoGroup($pid, $this->lang_id);
        if ($gallery) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($gallery as $view) {
                $this->domXml->create_element('video_gallery_group', '', 2);
                $this->domXml->set_attribute(array('gallery_group_id' => $view['GALLERY_GROUP_VIDEO_ID']
                    , 'level' => $level
                ));

                $subs_count = $this->Gallery->getSubVideGroupGallery($view['GALLERY_GROUP_VIDEO_ID']);

                $href = '';
                if ($subs_count > 0) {
                    $href = $lang . '/videogallery/all/n/' . $view['GALLERY_GROUP_VIDEO_ID'] . '/';
                } else {
                    $href = $lang . '/videogallery/view/n/' . $view['GALLERY_GROUP_VIDEO_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

//          if(!empty($_href) && $is_lang) $href = $lang.$_href;
//          elseif(!empty($_href) && !$is_lang) $href = $_href;

                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $gallery_chiled = $this->Gallery->getGalleryVideoGroup($view['GALLERY_GROUP_VIDEO_ID'],
                                                                       $this->lang_id);
                if (!empty($gallery_chiled)) {
                    $level++;
                    $this->getVideoGalleryGroup($view['GALLERY_GROUP_VIDEO_ID'],
                                                $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getGroupNews()
    {
        $news_group = $this->News->getNewsGroups($this->lang_id);
        if ($news_group) {
            $this->domXml->set_tag('//data', true);
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($news_group as $view) {

                $this->domXml->create_element('news_group', '', 2);
                $this->domXml->set_attribute(array('news_group_id' => $view['NEWS_GROUP_ID']));

                $href = $lang . '/news/all/n/' . $view['NEWS_GROUP_ID'] . '/';

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->getNews($view['NEWS_GROUP_ID']);

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getNews($news_group_id = 0, $startSelect = 0,
                             $news_per_page = 0)
    {
        $news = $this->News->getNews($news_group_id, $this->lang_id,
                                     $startSelect, $news_per_page);
        if ($news) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($news as $news_view) {
                $href = '';
                $is_url = 0;
                $is_lang = false;

                if (!empty($news_view['URL']) && strpos($news_view['URL'],
                                                        'http://') !== false) {
                    $is_lang = true;
                    $href = $news_view['URL'];
                } elseif (!empty($news_view['URL'])) {
                    $href = $news_view['URL'];
                    $is_url = 1;
                } else {
                    $is_lang = true;
                    $href = '/news/view/n/' . $news_view['NEWS_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array('news_id' => $news_view['NEWS_ID']
                    , 'news_group_id' => $news_view['NEWS_GROUP_ID']
                    , 'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $news_view['NAME']);

                $this->domXml->create_element('date', $news_view['date']);
                $this->domXml->create_element('url', $href);
                $this->domXml->go_to_parent();
            }
        }
    }

    private function getArticleGroups()
    {
        $article_group = $this->Article->articleGroup($this->lang_id);
        if ($article_group) {
            $this->domXml->set_tag('//data', true);
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($article_group as $view) {
                $href = $lang . '/articles/all/n/' . $view['ARTICLE_GROUP_ID'] . '/';

                $this->domXml->create_element('article_group', '', 2);
                $this->domXml->set_attribute(array('article_group_id' => $view['ARTICLE_GROUP_ID']));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->getArticles($view['ARTICLE_GROUP_ID']);

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getArticles($article_group_id = 0, $startSelect = 0,
                                 $article_per_page = 0)
    {
        $articles = $this->Article->getArticles($article_group_id,
                                                $this->lang_id, $startSelect,
                                                $article_per_page);
        if ($articles) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($articles as $art_view) {
                $this->domXml->create_element('articles', '', 2);
                $this->domXml->set_attribute(array('article_id' => $art_view['ARTICLE_ID']
                    , 'article_group_id' => $art_view['ARTICLE_GROUP_ID']
                ));
                $is_lang = false;
                $href = '';
                if (!empty($art_view['URL']) && strpos($art_view['URL'],
                                                       'http://') !== false) {
                    $is_lang = true;
                    $href = $art_view['URL'];
                } elseif (!empty($art_view['URL'])) {
                    $href = $art_view['URL'];
                } else {
                    $is_lang = true;
                    $href = '/articles/view/n/' . $art_view['ARTICLE_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('name', $art_view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }

}