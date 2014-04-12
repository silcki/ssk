<?php

header('Content-type: text/html; charset=UTF-8');
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

// Set the application root path
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_MODELS', ROOT_PATH . '/application/models/');
define('APPLICATION_CONTROLLERS', ROOT_PATH . '/application/controllers/');
// Set include path
set_include_path(ROOT_PATH . PATH_SEPARATOR . APPLICATION_MODELS . PATH_SEPARATOR . APPLICATION_CONTROLLERS);


require_once('../include/config.php');
require_once('admin/core.php');
//require_once('include/DOMxml.class.php');

$siteurl = 'http://ssk.ua';
$page_limit = 40000;

$cmf = &new SCMF();
//$domXml = &new DomXML('1.0','UTF-8');

require_once ZEND_PATH . '/Loader.php';

require_once ZEND_PATH . '/Session/Namespace.php';

Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');

Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');

$config = new Zend_Config_Ini('../config/config.ini.php', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);


$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);
$db->getConnection()->exec("SET character_set_server = utf8");
$db->getConnection()->exec("SET NAMES utf8");
$db->getConnection()->exec("SET CHARACTER SET utf8");
$db->getConnection()->exec("SET character_set_connection = utf8");
$db->getConnection()->exec('SET OPTION CHARACTER SET utf8');


$counter = 0;
//header('Content-Type: text/xml; charset=UTF-8');
$doc = new DOMDocument('1.0', 'UTF-8');
$root = $doc->createElement('urlset');
$root = $doc->appendChild($root);
$root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

if (!empty($_GET['id'])) {
    $total_cnt = 0;
    //Страницы для всех последующих,которые не влезли в $page_limit
    $news_count = $cmf->selectrow_array('select COUNT(*) from NEWS where STATUS=1');
    $art_count = $cmf->selectrow_array('select COUNT(*) from ARTICLE where STATUS=1');
    $doc_count = $cmf->selectrow_array('select COUNT(*) from ANOTHER_PAGES where STATUS=1');

    $query = "select distinct C.CATALOGUE_ID,C.REALCATNAME from CATALOGUE C inner join ITEM I on I.CATALOGUE_ID=C.CATALOGUE_ID where C.REALSTATUS=1 and C.COUNT_>0 AND I.STATUS=1 order by C.PARENT_ID,C.ORDERING";
    $result = $cmf->execute($query);
    $cats_count = mysql_num_rows($result);

    $total_cnt = $news_count + $art_count + $doc_count + $cats_count + 1;
    $items_count = $page_limit - $total_cnt;  //Товары на 1 странице

    if ($_GET['id'] > 1)
        $start = $items_count + ($_GET['id'] - 1) * $page_limit;
    else
        $start = $items_count;
    $que = "select ITEM_ID, NAME from ITEM where STATUS='1' order by ITEM_ID limit $start,$page_limit"; //echo $que; exit;
    $res = $cmf->execute($que);
    if (mysql_num_rows($res)) {
        while ($rw = mysql_fetch_array($res)) {
            $urname = preg_replace('/[^\w]/', '_', $cmf->translit(str_replace("...", "_", $rw['NAME'])));
            $urname = preg_replace("/_{2,}/", "_", $urname);

            $items = $doc->createElement('url');
            $items = $root->appendChild($items);

            $item_loc = $doc->createElement('loc');
            $item_loc = $items->appendChild($item_loc);
            $loc_val = $doc->createTextNode($siteurl . '/item/' . $rw['ITEM_ID'] . '/name/' . $urname . '/');
            $loc_val = $item_loc->appendChild($loc_val);

            $item_lastmod = $doc->createElement('lastmod');
            $item_lastmod = $items->appendChild($item_lastmod);
            $lastmod_val = $doc->createTextNode(date("c"));
            $lastmod_val = $item_lastmod->appendChild($lastmod_val);

            $item_changefreq = $doc->createElement('changefreq');
            $item_changefreq = $items->appendChild($item_changefreq);
            $changefreq_val = $doc->createTextNode("weekly");
            $changefreq_val = $item_changefreq->appendChild($changefreq_val);

            $item_priority = $doc->createElement('priority');
            $item_priority = $items->appendChild($item_priority);
            $priority_val = $doc->createTextNode('0.5');
            $priority_val = $item_priority->appendChild($priority_val);
        }
    }
} else {

//Индексная страница
    $main = $doc->createElement('url');
    $main = $root->appendChild($main);

    $main_loc = $doc->createElement('loc');
    $main_loc = $main->appendChild($main_loc);
    $loc_val = $doc->createTextNode($siteurl);
    $loc_val = $main_loc->appendChild($loc_val);

    $main_lastmod = $doc->createElement('lastmod');
    $main_lastmod = $main->appendChild($main_lastmod);
    $lastmod_val = $doc->createTextNode(date("c"));
    $lastmod_val = $main_lastmod->appendChild($lastmod_val);

    $main_changefreq = $doc->createElement('changefreq');
    $main_changefreq = $main->appendChild($main_changefreq);
    $changefreq_val = $doc->createTextNode("daily");
    $changefreq_val = $main_changefreq->appendChild($changefreq_val);

    $main_priority = $doc->createElement('priority');
    $main_priority = $main->appendChild($main_priority);
    $priority_val = $doc->createTextNode('1.0');
    $priority_val = $main_priority->appendChild($priority_val);

    $counter++;

//Категории
    $cnt = getCategs();
    $counter += $cnt;
    $left = $page_limit - $counter;

//Остальное товары до кол-ва $page_limit
    $que = "select ITEM_ID, CATALOGUE_ID from ITEM where STATUS='1' order by ITEM_ID limit 0,$left";
    $res = $cmf->execute($que);
    if (mysql_num_rows($res)) {
        while ($rw = mysql_fetch_array($res)) {
            $items = $doc->createElement('url');
            $items = $root->appendChild($items);

            $item_loc = $doc->createElement('loc');
            $item_loc = $items->appendChild($item_loc);
            $loc_val = $doc->createTextNode($siteurl . '/cat/item/n/' . $rw['CATALOGUE_ID'] . '/it/' . $rw['ITEM_ID'] . '/');
            $loc_val = $item_loc->appendChild($loc_val);

            $item_lastmod = $doc->createElement('lastmod');
            $item_lastmod = $items->appendChild($item_lastmod);
            $lastmod_val = $doc->createTextNode(date("c"));
            $lastmod_val = $item_lastmod->appendChild($lastmod_val);

            $item_changefreq = $doc->createElement('changefreq');
            $item_changefreq = $items->appendChild($item_changefreq);
            $changefreq_val = $doc->createTextNode("weekly");
            $changefreq_val = $item_changefreq->appendChild($changefreq_val);

            $item_priority = $doc->createElement('priority');
            $item_priority = $items->appendChild($item_priority);
            $priority_val = $doc->createTextNode('0.5');
            $priority_val = $item_priority->appendChild($priority_val);
        }
    }

//Новости
    $sql = 'select NEWS_ID,NAME,DATE_FORMAT(DATA,"%Y-%m-%dT%H:%i:%s") as date from NEWS where STATUS=1 order by NEWS_ID desc';
    $sth = $cmf->execute($sql);
    while ($rws = mysql_fetch_array($sth)) {
        $news = $doc->createElement('url');
        $news = $root->appendChild($news);

        $news_loc = $doc->createElement('loc');
        $news_loc = $news->appendChild($news_loc);
        $loc_val = $doc->createTextNode($siteurl . '/news/all/n/' . $rws['NEWS_ID'] . '/');
        $loc_val = $news_loc->appendChild($loc_val);

        $news_lastmod = $doc->createElement('lastmod');
        $news_lastmod = $news->appendChild($news_lastmod);
        $lastmod_val = $doc->createTextNode($rws['date'] . '+02:00');
        $lastmod_val = $news_lastmod->appendChild($lastmod_val);

        $news_changefreq = $doc->createElement('changefreq');
        $news_changefreq = $news->appendChild($news_changefreq);
        $changefreq_val = $doc->createTextNode("monthly");
        $changefreq_val = $news_changefreq->appendChild($changefreq_val);

        $news_priority = $doc->createElement('priority');
        $news_priority = $news->appendChild($news_priority);
        $priority_val = $doc->createTextNode('0.5');
        $priority_val = $news_priority->appendChild($priority_val);

        $counter++;
    }

//Статьи
    $sql = 'select ARTICLE_ID,NAME,DATE_FORMAT(DATA,"%Y-%m-%dT%H:%i:%s") as date from ARTICLE where STATUS=1 order by ARTICLE_ID desc';
    $sth = $cmf->execute($sql);
    while ($rws = mysql_fetch_array($sth)) {
        $articles = $doc->createElement('url');
        $articles = $root->appendChild($articles);

        $articles_loc = $doc->createElement('loc');
        $articles_loc = $articles->appendChild($articles_loc);
        $loc_val = $doc->createTextNode($siteurl . '/articles/all/n/' . $rws['ARTICLE_ID'] . '/');
        $loc_val = $articles_loc->appendChild($loc_val);

        $articles_lastmod = $doc->createElement('lastmod');
        $articles_lastmod = $articles->appendChild($articles_lastmod);
        $lastmod_val = $doc->createTextNode($rws['date'] . '+02:00');
        $lastmod_val = $articles_lastmod->appendChild($lastmod_val);

        $articles_changefreq = $doc->createElement('changefreq');
        $articles_changefreq = $articles->appendChild($articles_changefreq);
        $changefreq_val = $doc->createTextNode("monthly");
        $changefreq_val = $articles_changefreq->appendChild($changefreq_val);

        $articles_priority = $doc->createElement('priority');
        $articles_priority = $articles->appendChild($articles_priority);
        $priority_val = $doc->createTextNode('0.5');
        $priority_val = $articles_priority->appendChild($priority_val);

        $counter++;
    }


//Страницы сайта

    $sql = 'select ANOTHER_PAGES_ID,NAME,REALCATNAME,URL from ANOTHER_PAGES where STATUS=1 order by PARENT_ID,ANOTHER_PAGES_ID';
    $sth = $cmf->execute($sql);
    while ($rws = mysql_fetch_array($sth)) {
        $pages = $doc->createElement('url');
        $pages = $root->appendChild($pages);

        if (!empty($rws['URL'])) {
            $purl = $rws['URL'];
        } else {
            if (!empty($rws['REALCATNAME']) && $rws['REALCATNAME'] != '/')
                $purl = '/doc' . $rws['REALCATNAME'];
            else
                $purl = '/doc/' . $rws['ANOTHER_PAGES_ID'] . '/';
        }

        $pages_loc = $doc->createElement('loc');
        $pages_loc = $pages->appendChild($pages_loc);
        $loc_val = $doc->createTextNode($siteurl . $purl);
        $loc_val = $pages_loc->appendChild($loc_val);

        $pages_lastmod = $doc->createElement('lastmod');
        $pages_lastmod = $pages->appendChild($pages_lastmod);
        $lastmod_val = $doc->createTextNode(date("c"));
        $lastmod_val = $pages_lastmod->appendChild($lastmod_val);

        $pages_changefreq = $doc->createElement('changefreq');
        $pages_changefreq = $pages->appendChild($pages_changefreq);
        $changefreq_val = $doc->createTextNode("monthly");
        $changefreq_val = $pages_changefreq->appendChild($changefreq_val);

        $pages_priority = $doc->createElement('priority');
        $pages_priority = $pages->appendChild($pages_priority);
        $priority_val = $doc->createTextNode('0.5');
        $priority_val = $pages_priority->appendChild($priority_val);

        $counter++;
    }
}
$xml = $doc->saveXML();
echo $xml;

function getCategs()
{
    global $cmf, $siteurl, $doc, $root;
    $query = "select distinct CATALOGUE_ID,REALCATNAME from CATALOGUE where REALSTATUS=1 order by PARENT_ID,ORDERING";
    $result = $cmf->execute($query);
    $cnt = mysql_num_rows($result);
    while ($row = mysql_fetch_array($result)) {
        if ($row['CATALOGUE_ID'] > 0) {
            $cats = $doc->createElement('url');
            $cats = $root->appendChild($cats);

            if (!empty($row['REALCATNAME']) && $row['REALCATNAME'] != '/')
                $catname = $row['REALCATNAME'];
            else
                $catname = '/' . $row['CATALOGUE_ID'] . '/';

            $cat_loc = $doc->createElement('loc');
            $cat_loc = $cats->appendChild($cat_loc);
            $loc_val = $doc->createTextNode($siteurl . '/cat' . $catname);
            $loc_val = $cat_loc->appendChild($loc_val);

            $cat_lastmod = $doc->createElement('lastmod');
            $cat_lastmod = $cats->appendChild($cat_lastmod);
            $lastmod_val = $doc->createTextNode(date("c"));
            $lastmod_val = $cat_lastmod->appendChild($lastmod_val);

            $cat_changefreq = $doc->createElement('changefreq');
            $cat_changefreq = $cats->appendChild($cat_changefreq);
            $changefreq_val = $doc->createTextNode("daily");
            $changefreq_val = $cat_changefreq->appendChild($changefreq_val);

            $cat_priority = $doc->createElement('priority');
            $cat_priority = $cats->appendChild($cat_priority);
            $priority_val = $doc->createTextNode('0.8');
            $priority_val = $cat_priority->appendChild($priority_val);
        }
    }
    return $cnt;
}

/*
function getTree($parent_id, $cnt=0)
{
   global $cmf,$siteurl,$doc,$root;
   $query = "select CATALOGUE_ID,PARENT_ID,NAME,REALCATNAME from CATALOGUE where PARENT_ID='".$parent_id."' and REALSTATUS=1 and COUNT_>0 order by ORDERING";
   $result = $cmf->execute($query);
   while($row = mysql_fetch_array($result))
   {
      if($row['CATALOGUE_ID'] > 0)
      {
         $cats =  $doc->createElement('url');
         $cats = $root->appendChild($cats);

         if(!empty($row['REALCATNAME']) && $row['REALCATNAME'] != '/') $catname = $row['REALCATNAME'];
         else $catname = '/'.$row['CATALOGUE_ID'].'/';

         $cat_loc = $doc->createElement('loc');
         $cat_loc = $cats->appendChild($cat_loc);
         $loc_val = $doc->createTextNode($siteurl.'/cat'.$catname);
         $loc_val =  $cat_loc->appendChild($loc_val);

         $cat_lastmod = $doc->createElement('lastmod');
         $cat_lastmod = $cats->appendChild($cat_lastmod);
         $lastmod_val = $doc->createTextNode(date("c"));
         $lastmod_val =  $cat_lastmod->appendChild($lastmod_val);

         $cat_changefreq = $doc->createElement('changefreq');
         $cat_changefreq = $cats->appendChild($cat_changefreq);
         $changefreq_val = $doc->createTextNode("daily");
         $changefreq_val =  $cat_changefreq->appendChild($changefreq_val);

         $cat_priority = $doc->createElement('priority');
         $cat_priority = $cats->appendChild($cat_priority);
         $priority_val = $doc->createTextNode('0.8');
         $priority_val =  $cat_priority->appendChild($priority_val);

         //Продукты
         $que = "select ITEM_ID, NAME from ITEM where CATALOGUE_ID='".$row['CATALOGUE_ID']."' and STATUS='1'";
         $res = $cmf->execute($que);
         if(mysql_num_rows($res))
         {
            while($rw = mysql_fetch_array($res))
            {
               $urname=preg_replace('/[^\w]/', '_', $cmf->translit(str_replace("...","_",$rw['NAME'])));
               $urname = preg_replace("/_{2,}/","_",$urname);

               $items =  $doc->createElement('url');
               $items = $root->appendChild($items);

               $item_loc = $doc->createElement('loc');
               $item_loc = $items->appendChild($item_loc);
               $loc_val = $doc->createTextNode($siteurl.'/item/'.$rw['ITEM_ID'].'/name/'.$urname.'/');
               $loc_val =  $item_loc->appendChild($loc_val);

               $item_lastmod = $doc->createElement('lastmod');
               $item_lastmod = $items->appendChild($item_lastmod);
               $lastmod_val = $doc->createTextNode(date("c"));
               $lastmod_val =  $item_lastmod->appendChild($lastmod_val);

               $item_changefreq = $doc->createElement('changefreq');
               $item_changefreq = $items->appendChild($item_changefreq);
               $changefreq_val = $doc->createTextNode("weekly");
               $changefreq_val =  $item_changefreq->appendChild($changefreq_val);

               $item_priority = $doc->createElement('priority');
               $item_priority = $items->appendChild($item_priority);
               $priority_val = $doc->createTextNode('0.5');
               $priority_val =  $item_priority->appendChild($priority_val);

            }
         }
         $cnt++;
         if($cnt >= 40000) break;
         getTree($row['CATALOGUE_ID'],$cnt);
      }
   }
}
*/


