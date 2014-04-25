<?php
class HelperArticles extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Article
     */
    protected $articles;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->articles = $this->getServiceManager()->getModel()->getArticle();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    public function getArticleCount()
    {
        return $this->articles->getArticleCount();
    }

    /**
     * Метод для получения XML подробного текста статьи
     * @access   public
     * @return   $this
     */
    public function getArticleSingle($articleId)
    {
        $article = $this->articles->getArticleSingle($articleId, $this->params['langId']);

        if (!empty($article)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('article_single', '', 2);
            $this->domXml->set_attribute(array('article_id' => $article['ARTICLE_ID']));

            $this->domXml->create_element('name', $article['NAME']);
            $this->domXml->create_element('date', $article['date']);

            $this->getDocXml($articleId, 5, true, $this->params['langId']);
            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @param $articleGroupId
     * @param int $startSelect
     * @param int $article_per_page
     *
     * @return $this
     */
    public function getArticles($articleGroupId, $startSelect = 0, $article_per_page = 0)
    {
        $articles = $this->articles->getArticles($articleGroupId, $this->params['langId'], $startSelect, $article_per_page);
        if ($articles) {
            $lang = '';

            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($articles as $art_view) {
                $this->domXml->create_element('articles', '', 2);
                $this->domXml->set_attribute(array(
                    'article_id' => $art_view['ARTICLE_ID']
                , 'article_group_id' => $art_view['ARTICLE_GROUP_ID']
                ));
                $is_lang = false;
                $href = '';
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
                $this->domXml->create_element('descript', $art_view['descript']);
                $this->domXml->create_element('date', $art_view['date']);
                $this->domXml->create_element('url', $href);

                if (!empty($art_view['IMAGE1']) && strchr($art_view['IMAGE1'], "#")) {
                    $tmp = explode('#', $art_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * Мета описание
     *
     * @param int $articleId
     * @return $this
     */
    public function getMetaSingle($articleId)
    {
        $article = $this->articles->getArticleSingle($articleId, $this->params['langId']);
        if (!empty($article)) {
            $this->domXml->create_element('doc_meta', '', 2);

            $this->domXml->create_element('name', $article['NAME']);

            $this->domXml->create_element('title', $article['NAME']);
            $this->domXml->create_element('description', $article['NAME']);
            $this->domXml->create_element('keywords', $article['NAME']);

            $this->domXml->go_to_parent();
        }

        return $this;
    }
} 