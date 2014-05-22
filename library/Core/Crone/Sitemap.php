<?php
class Core_Crone_Sitemap extends Core_Crone_Abstract
{
    private $doc;
    private $root;
    private $page_limit = 40000;
    private $siteurl = 'http://ssk.ua';
    private $left = 0;
    private $page = 1;

    private $_catalogue;
    private $_anotherPages;
    private $_article;
    private $_news;

    public function init()
    {
        $this->_anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
        $this->_catalogue = $this->getServiceManager()->getModel()->getCatalogue();
        $this->_article = $this->getServiceManager()->getModel()->getArticle();
        $this->_news = $this->getServiceManager()->getModel()->getNews();
    }

    function run()
    {
        $file_name = '';
        $this->createMap();
        $this->createIndex();

        $this->getCategs(0);
        $this->getAnPages();
        $this->getNews();
        $this->getArticle();

        $file_name = 'sitemap.xml';
        $this->saveFile($file_name);
    }

    function createMap()
    {
        $this->doc = new DOMDocument('1.0', 'UTF-8');
        $this->root = $this->doc->createElement('urlset');
        $this->root = $this->doc->appendChild($this->root);
        $this->root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }

    function createIndex()
    {
        //Индексная страница
        $main = $this->doc->createElement('url');
        $main = $this->root->appendChild($main);

        $main_loc = $this->doc->createElement('loc');
        $main_loc = $main->appendChild($main_loc);
        $loc_val = $this->doc->createTextNode($this->siteurl);
        $loc_val = $main_loc->appendChild($loc_val);

        $main_lastmod = $this->doc->createElement('lastmod');
        $main_lastmod = $main->appendChild($main_lastmod);
        $lastmod_val = $this->doc->createTextNode(date("c"));
        $lastmod_val = $main_lastmod->appendChild($lastmod_val);

        $main_changefreq = $this->doc->createElement('changefreq');
        $main_changefreq = $main->appendChild($main_changefreq);
        $changefreq_val = $this->doc->createTextNode("daily");
        $changefreq_val = $main_changefreq->appendChild($changefreq_val);

        $main_priority = $this->doc->createElement('priority');
        $main_priority = $main->appendChild($main_priority);
        $priority_val = $this->doc->createTextNode('1.0');
        $priority_val = $main_priority->appendChild($priority_val);
    }

    function limit()
    {
        if ($this->left <= 0) {
            $this->page++;
            $this->left = 0;
        }

        $startSelect = $this->page * $this->page_limit;
        $startSelect = $startSelect > $this->left ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        return $startSelect;
    }

    function getCategs($parentId, $level = 2)
    {
        $cnt = 0;
        if ($level == 4) {
            return false;
        }
        $catalogs = $this->_catalogue->getTree($parentId);

        if (!empty($catalogs)) {
            $cnt = count($catalogs);
            foreach ($catalogs as $view) {
                $cats = $this->doc->createElement('url');
                $cats = $this->root->appendChild($cats);

                $children_item_count = $this->_catalogue->getItemsCount($view['CATALOGUE_ID']);
                $href = '';
                if (($view['ITEM_IS_DESCR'] == 1) && ($children_item_count == 1)) {
                    $item_id = $this->_catalogue->getCatFirstItems($view['CATALOGUE_ID']);
                    $href = '/cat/item/n/' . $view['CATALOGUE_ID'] . '/it/' . $item_id . '/';
                } elseif ($children_item_count > 0) {
                    $href = '/cat/view/n/' . $view['CATALOGUE_ID'] . '/';
                } else {
                    if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                        $href = $view['URL'];
                    } elseif (!empty($cat['URL'])) {
                        $href = $view['URL'];
                    } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                        $href = '/cat' . $view['REALCATNAME'];
                    } else {
                        $href = '/cat/' . $view['CATALOGUE_ID'] . '/';
                    }
                }

                $_href = $this->_anotherPages->getSefURLbyOldURL($href);

                $cat_loc = $this->doc->createElement('loc');
                $cat_loc = $cats->appendChild($cat_loc);
                $loc_val = $this->doc->createTextNode($this->siteurl . $_href);
                $loc_val = $cat_loc->appendChild($loc_val);

                $cat_lastmod = $this->doc->createElement('lastmod');
                $cat_lastmod = $cats->appendChild($cat_lastmod);
                $lastmod_val = $this->doc->createTextNode(date("c"));
                $lastmod_val = $cat_lastmod->appendChild($lastmod_val);

                $changefreq = 'weekly';
                $priority = '0.5';
                switch ($level) {
                    case 2:
                        $changefreq = 'weekly';
                        $priority = '0.5';
                        break;

                    case 3:
                        $changefreq = 'daily';
                        $priority = '0.9';
                        break;

                    case 5:
                        $changefreq = 'daily';
                        $priority = '0.8';
                        break;

                    default:
                        $changefreq = 'weekly';
                        $priority = '0.5';
                        break;
                }
                $cat_changefreq = $this->doc->createElement('changefreq');
                $cat_changefreq = $cats->appendChild($cat_changefreq);
                $changefreq_val = $this->doc->createTextNode($changefreq);
                $changefreq_val = $cat_changefreq->appendChild($changefreq_val);

                $cat_priority = $this->doc->createElement('priority');
                $cat_priority = $cats->appendChild($cat_priority);
                $priority_val = $this->doc->createTextNode($priority);
                $priority_val = $cat_priority->appendChild($priority_val);

                $level++;
                $this->getCategs($view['CATALOGUE_ID'], $level);
                $level--;
            }

        }

        return $cnt;
    }

    function getAnPages()
    {
        $another_pages = $this->_anotherPages->getSiteMapTree();

        if (!empty($another_pages)) {
            $cnt = count($another_pages);
            foreach ($another_pages as $view) {
                $cats = $this->doc->createElement('url');
                $cats = $this->root->appendChild($cats);

                $href = '';
                $is_url = true;
                if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                    $is_url = false;
                    $href = $view['URL'];
                } elseif (!empty($view['URL'])) {
                    $href = $view['URL'];
                } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                    $href = $view['REALCATNAME'];
                } else {
                    $href = '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                }

                $_href = $this->_anotherPages->getSefURLbyOldURL($href);
                $href = !empty($_href) ? $_href : $href;

                $href = $is_url ? $this->siteurl . $href : $href;

                $cat_loc = $this->doc->createElement('loc');
                $cat_loc = $cats->appendChild($cat_loc);
                $loc_val = $this->doc->createTextNode($href);
                $loc_val = $cat_loc->appendChild($loc_val);

                $cat_lastmod = $this->doc->createElement('lastmod');
                $cat_lastmod = $cats->appendChild($cat_lastmod);
                $lastmod_val = $this->doc->createTextNode(date("c"));
                $lastmod_val = $cat_lastmod->appendChild($lastmod_val);

                $cat_changefreq = $this->doc->createElement('changefreq');
                $cat_changefreq = $cats->appendChild($cat_changefreq);
                $changefreq_val = $this->doc->createTextNode('weekly');
                $changefreq_val = $cat_changefreq->appendChild($changefreq_val);

                $cat_priority = $this->doc->createElement('priority');
                $cat_priority = $cats->appendChild($cat_priority);
                $priority_val = $this->doc->createTextNode('0.5');
                $priority_val = $cat_priority->appendChild($priority_val);
            }

        }

        return $cnt;
    }

    function getNews()
    {
        $news = $this->_news->getSiteMapNews();
        $cnt = count($news);
        if (!empty($news)) {
            foreach ($news as $view) {
                $cats = $this->doc->createElement('url');
                $cats = $this->root->appendChild($cats);

                $is_url = true;
                if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                    $is_url = false;
                    $href = $view['URL'];
                } elseif (!empty($view['URL'])) {
                    $href = $view['URL'];
                } else {
                    $href = '/news/view/n/' . $view['NEWS_ID'] . '/';
                }

                $_href = $this->_anotherPages->getSefURLbyOldURL($href);
                $href = !empty($_href) ? $_href : $href;

                $href = $is_url ? $this->siteurl . $href : $href;

                $cat_loc = $this->doc->createElement('loc');
                $cat_loc = $cats->appendChild($cat_loc);
                $loc_val = $this->doc->createTextNode($href);
                $loc_val = $cat_loc->appendChild($loc_val);

                $cat_lastmod = $this->doc->createElement('lastmod');
                $cat_lastmod = $cats->appendChild($cat_lastmod);
                $lastmod_val = $this->doc->createTextNode(date("c"));
                $lastmod_val = $cat_lastmod->appendChild($lastmod_val);

                $cat_changefreq = $this->doc->createElement('changefreq');
                $cat_changefreq = $cats->appendChild($cat_changefreq);
                $changefreq_val = $this->doc->createTextNode('weekly');
                $changefreq_val = $cat_changefreq->appendChild($changefreq_val);

                $cat_priority = $this->doc->createElement('priority');
                $cat_priority = $cats->appendChild($cat_priority);
                $priority_val = $this->doc->createTextNode('0.5');
                $priority_val = $cat_priority->appendChild($priority_val);
            }

        }

        return $cnt;
    }

    function getArticle()
    {
        $article = $this->_article->getSiteMapArticle();
        $cnt = count($article);
        if (!empty($article)) {
            foreach ($article as $view) {
                $cats = $this->doc->createElement('url');
                $cats = $this->root->appendChild($cats);

                $href = '';
                if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                    $href = $view['URL'];
                } elseif (!empty($view['URL'])) {
                    $href = $view['URL'];
                } else {
                    $href = '/articles/view/n/' . $view['ARTICLE_ID'] . '/';
                }

                $_href = $this->_anotherPages->getSefURLbyOldURL($href);
                $href = !empty($_href) ? $_href : $href;

                $cat_loc = $this->doc->createElement('loc');
                $cat_loc = $cats->appendChild($cat_loc);
                $loc_val = $this->doc->createTextNode($this->siteurl . $href);
                $loc_val = $cat_loc->appendChild($loc_val);

                $cat_lastmod = $this->doc->createElement('lastmod');
                $cat_lastmod = $cats->appendChild($cat_lastmod);
                $lastmod_val = $this->doc->createTextNode(date("c"));
                $lastmod_val = $cat_lastmod->appendChild($lastmod_val);

                $cat_changefreq = $this->doc->createElement('changefreq');
                $cat_changefreq = $cats->appendChild($cat_changefreq);
                $changefreq_val = $this->doc->createTextNode('weekly');
                $changefreq_val = $cat_changefreq->appendChild($changefreq_val);

                $cat_priority = $this->doc->createElement('priority');
                $cat_priority = $cats->appendChild($cat_priority);
                $priority_val = $this->doc->createTextNode('0.5');
                $priority_val = $cat_priority->appendChild($priority_val);
            }

        }

        return $cnt;
    }

    function saveFile($file_name)
    {
        $dir_path = SITE_PATH . '/' . $file_name;

        if (is_file(SITE_PATH . '/' . $file_name)) {
            unlink(SITE_PATH . '/' . $file_name);
        }

        $xml = isset($this->doc) ? $this->doc->saveXML() : '';

        if (!empty($xml)) {
            $handle = fopen(SITE_PATH . '/' . $file_name, 'a');
            fwrite($handle, $xml);
            fclose($handle);
            chmod(SITE_PATH . '/' . $file_name, 0644);
        }
    }
}