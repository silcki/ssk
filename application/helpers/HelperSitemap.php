<?php
class HelperSitemap extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Gallery
     */
    protected $gallery;

    /**
     * @var News
     */
    protected $news;

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->gallery = $this->getServiceManager()->getModel()->getGallery();
        $this->news = $this->getServiceManager()->getModel()->getNews();
        $this->article = $this->getServiceManager()->getModel()->getArticle();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    /**
     * @param $pid
     * @param $level
     *
     * @return $this
     */
    public function getGalleryGroup($pid, $level)
    {
        $gallery = $this->gallery->getGalleryGroup($pid, $this->params['langId']);
        if ($gallery) {

            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($gallery as $view) {
                $this->domXml->create_element('gallery_group', '', 2);
                $this->domXml->set_attribute(array('gallery_group_id' => $view['GALLERY_GROUP_ID']
                , 'level' => $level
                ));

                $subs_count = $this->gallery->getSubGroupGallery($view['GALLERY_GROUP_ID']);

                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $view['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $view['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $gallery_chiled = $this->gallery->getGalleryGroup($view['GALLERY_GROUP_ID'],
                    $this->params['langId']);
                if (!empty($gallery_chiled)) {
                    $level++;
                    $this->getGalleryGroup($view['GALLERY_GROUP_ID'], $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @param $pid
     * @param $level
     *
     * @return $this
     */
    public function getVideoGalleryGroup($pid, $level)
    {
        $gallery = $this->gallery->getGalleryVideoGroup($pid, $this->params['langId']);

        if ($gallery) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($gallery as $view) {
                $this->domXml->create_element('video_gallery_group', '', 2);
                $this->domXml->set_attribute(array('gallery_group_id' => $view['GALLERY_GROUP_VIDEO_ID']
                , 'level' => $level
                ));

                $subs_count = $this->gallery->getSubVideGroupGallery($view['GALLERY_GROUP_VIDEO_ID']);

                $href = '';
                if ($subs_count > 0) {
                    $href = $lang . '/videogallery/all/n/' . $view['GALLERY_GROUP_VIDEO_ID'] . '/';
                } else {
                    $href = $lang . '/videogallery/view/n/' . $view['GALLERY_GROUP_VIDEO_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $gallery_chiled = $this->gallery->getGalleryVideoGroup($view['GALLERY_GROUP_VIDEO_ID'],
                    $this->params['langId']);
                if (!empty($gallery_chiled)) {
                    $level++;
                    $this->getVideoGalleryGroup($view['GALLERY_GROUP_VIDEO_ID'],
                        $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getGroupNews()
    {
        $news_group = $this->news->getNewsGroups($this->params['langId']);
        if ($news_group) {

            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

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

        return $this;
    }

    /**
     * @param int $news_group_id
     * @param int $startSelect
     * @param int $news_per_page
     *
     * @return $this
     */
    public function getNews($news_group_id = 0, $startSelect = 0, $news_per_page = 0)
    {
        $news = $this->news->getNews($news_group_id, $this->params['langId'], $startSelect, $news_per_page);
        if ($news) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

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

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array(
                    'news_id' => $news_view['NEWS_ID'],
                    'news_group_id' => $news_view['NEWS_GROUP_ID'],
                    'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $news_view['NAME']);

                $this->domXml->create_element('date', $news_view['date']);
                $this->domXml->create_element('url', $href);
                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getArticleGroups()
    {
        $article_group = $this->article->articleGroup($this->params['langId']);
        if ($article_group) {
            $this->domXml->set_tag('//data', true);
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

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

        return $this;
    }

    /**
     * @param int $article_group_id
     * @param int $startSelect
     * @param int $article_per_page
     *
     * @return $this
     */
    public function getArticles($article_group_id = 0, $startSelect = 0, $article_per_page = 0)
    {
        $articles = $this->article->getArticles($article_group_id, $this->params['langId'], $startSelect, $article_per_page);

        if ($articles) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($articles as $art_view) {
                $this->domXml->create_element('articles', '', 2);

                $this->domXml->set_attribute(array('article_id' => $art_view['ARTICLE_ID']
                , 'article_group_id' => $art_view['ARTICLE_GROUP_ID']
                ));

                $is_lang = false;

                if (!empty($art_view['URL']) && strpos($art_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $art_view['URL'];
                } elseif (!empty($art_view['URL'])) {
                    $href = $art_view['URL'];
                } else {
                    $is_lang = true;
                    $href = '/articles/view/n/' . $art_view['ARTICLE_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('name', $art_view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 