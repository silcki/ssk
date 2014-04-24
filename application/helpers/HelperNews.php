<?php
class HelperNews extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var News
     */
    protected $news;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->news = $this->getServiceManager()->getModel()->getNews();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    public function getIndexNews($news_index_amount)
    {
        $news = $this->news->getNewsIndex(0, $this->params['langId'], $news_index_amount);
        if (!empty($news)) {
            $this->domXml->set_tag('//data', true);

            if ($this->params['langId'] > 0) {
                $lang = '/' .$this->params['lang'];
            } else {
                $lang = '';
            }

            foreach ($news as $n_view) {
                $is_url = 0;

                $is_lang = false;
                if (!empty($n_view['URL']) && strpos($n_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $n_view['URL'];
                } elseif (!empty($n_view['URL'])) {
                    $href = $n_view['URL'];
                    $is_url = 1;
                } else {
                    $is_lang = true;
                    $href = '/news/view/n/' . $n_view['NEWS_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array('id' => $n_view['NEWS_ID']
                , 'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $n_view['NAME']);
                $this->domXml->create_element('date', $n_view['date']);
                $this->domXml->create_element('url', $href);

                if (!empty($n_view['IMAGE1']) && strchr($n_view['IMAGE1'], "#")) {
                    $tmp = explode('#', $n_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                        )
                    );
                    $this->domXml->go_to_parent();
                }

                $this->setXmlNode($n_view['descript'], 'descript');
                $this->domXml->go_to_parent();
            }
        }

    }

    /**
     * @param int $newsGroupId (не используется)
     * @param int $startSelect
     * @param int $newsPerPage
     *
     * @return $this
     */
    public function getNews($newsGroupId, $startSelect = 0, $newsPerPage = 0)
    {
        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        $news = $this->news->getNews($newsGroupId, $this->params['langId'], $startSelect, $newsPerPage);
        if ($news) {

            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($news as $news_view) {
                $is_url = 0;
                $is_lang = false;

                if (!empty($news_view['URL']) && strpos($news_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $news_view['URL'];
                } elseif (!empty($news_view['URL'])) {
                    $href = $news_view['URL'];
                    $is_url = 1;
                } else {
                    $is_lang = true;
                    $href = '/news/view/n/' . $news_view['NEWS_ID'] . '/';
                }

                $_href = $anotherPagesModel->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array('news_id' => $news_view['NEWS_ID']
                , 'news_group_id' => $news_view['NEWS_GROUP_ID']
                , 'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $news_view['NAME']);

                $this->domXml->create_element('date', $news_view['date']);
                $this->domXml->create_element('url', $href);

                if (!empty($news_view['IMAGE1']) && strchr($news_view['IMAGE1'], "#")) {
                    $tmp = explode('#', $news_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->setXmlNode($news_view['descript'], 'descript');
                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * Метод для получения XML подробного текста новости
     *
     * @access public
     *
     * @return $this
     */
    public function getNewsSingle($newsId)
    {
        $news = $this->news->getNewsSingle($newsId, $this->params['langId']);
        if (!empty($news)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('news_single', '', 2);
            $this->domXml->set_attribute(array('news_id' => $news['NEWS_ID']));

            $this->domXml->create_element('name', $news['NAME']);
            $this->domXml->create_element('date', $news['date']);

            $this->getDocXml($newsId, 1, true, $this->params['langId']);
            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * Получить мета описание новости
     *
     * @param int $newsId
     *
     * @return $this
     */
    public function getMetaSingle($newsId)
    {
        $news = $this->news->getNewsSingle($newsId, $this->params['langId']);

        if (!empty($news)) {
            $this->domXml->create_element('doc_meta', '', 2);

            $this->domXml->create_element('name', $news['NAME']);

            $this->domXml->create_element('title', $news['NAME']);
            $this->domXml->create_element('description', $news['NAME']);
            $this->domXml->create_element('keywords', $news['NAME']);

            $this->domXml->go_to_parent();
        }

        return $this;
    }


    /**
     * @return int
     */
    public function getNewsCount()
    {
        return $this->news->getNewsCount(0);
    }
} 