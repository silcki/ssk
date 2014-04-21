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
} 