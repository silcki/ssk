<?php
require_once ROOT_PATH . "/lib/Translit.class.php";
require_once ROOT_PATH . "/lib/createSEFU_DB.class.php";

define('SEFU_PREFIX_CAT_URL_FOR_ADMIN', 'cat');
define('SEFU_PREFIX_ARTICLE_URL', 'articles');
define('SEFU_PREFIX_ARTICLE_URL_FOR_ADMIN', 'articles');
define('SEFU_PREFIX_NEWS_URL', 'news');
define('SEFU_PREFIX_PROJECTS_URL', 'projects');
define('SEFU_PREFIX_DOC_URL', 'doc');
define('SEFU_PREFIX_CAT_URL', 'catalog');
define('SEFU_PREFIX_GALLERY_URL', 'gallery');
define('SEFU_PREFIX_VIDEO_GALLERY_URL', 'videogallery');

/**
 * Класс CreateSEFU (SEFU - Search Engine Frendly Urls) предназначен для
 * формирования SEF-урлов
 *
 * @author Администратор
 */
class CreateSEFU
{
    protected $help;

    public function __construct()
    {
        $this->help = new createSEFU_DB();
    }

    /**
     * Метод applySEFU сохраняет все урлы сайта в формате SEFU
     */
    public function applySEFU()
    {
        $this->applySEFUCatalogue();
        $this->applySEFUArticles();
        $this->applySEFUNews();
        $this->applySEFUProjects();
        $this->applySEFUDoc();
        $this->applySEFUItem();
        $this->applySEFUGallery();
        $this->applySEFUVideoGallery();
    }

    /**
     * Метод applySEFUCatalogueIndex формирует урлы каталога в формате SEFU. для индексной
     * Надо его отрефакторить.
     */
    public function applySEFUFaqIndex()
    {
        $siteURL = '/faq/'; // получаем REALCATNAME

        $docId = $this->help->getDocIdByUrl($siteURL);
        $sefURL = $this->help->getDocRealCat($docId);

        if ($sefURL) {
            $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

            if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                    // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                    // то существующий записываем в старые, а полученный - на место существующего
                    $this->help->addOldURL($idSefSiteRelation,
                        $sefSiteRelationInfo['SEF_URL']);
                    $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                        $sefURL);
                }
            } else
                $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                    $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
            /*             * ****** формируем OLD ******* */

            $oldURL = $siteURL;

            $this->help->addOldURL($idSefSiteRelation, $oldURL);
        }
    }

    /**
     * Метод applySEFUCatalogueIndex формирует урлы каталога в формате SEFU. для индексной
     * Надо его отрефакторить.
     */
    public function applySEFUGalleryIndex()
    {
        $siteURL = "/" . SEFU_PREFIX_GALLERY_URL . '/'; // получаем REALCATNAME

        $docId = $this->help->getDocIdByUrl($siteURL);
        $sefURL = $this->help->getDocRealCat($docId);

        if ($sefURL) {
            $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

            if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                    // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                    // то существующий записываем в старые, а полученный - на место существующего
                    $this->help->addOldURL($idSefSiteRelation,
                        $sefSiteRelationInfo['SEF_URL']);
                    $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                        $sefURL);
                }
            } else
                $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                    $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
            /*             * ****** формируем OLD ******* */

            $oldURL = $siteURL;

            $this->help->addOldURL($idSefSiteRelation, $oldURL);
        }
    }

    /**
     * Метод applySEFUCatalogueIndex формирует урлы каталога в формате SEFU. для индексной
     * Надо его отрефакторить.
     */
    public function applySEFUVideoGalleryIndex()
    {
        $siteURL = "/" . SEFU_PREFIX_VIDEO_GALLERY_URL . '/'; // получаем REALCATNAME

        $docId = $this->help->getDocIdByUrl($siteURL);
        $sefURL = $this->help->getDocRealCat($docId);

        if ($sefURL) {
            $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

            if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                    // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                    // то существующий записываем в старые, а полученный - на место существующего
                    $this->help->addOldURL($idSefSiteRelation,
                        $sefSiteRelationInfo['SEF_URL']);
                    $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                        $sefURL);
                }
            } else
                $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                    $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
            /*             * ****** формируем OLD ******* */

            $oldURL = $siteURL;

            $this->help->addOldURL($idSefSiteRelation, $oldURL);
        }
    }

    /**
     * Метод applySEFUGallery формирует урлы галереи в формате SEFU.
     * Надо его отрефакторить.
     */
    public function applySEFUGallery()
    {
        $cats = $this->help->getGallery(); // достаем ID всех каталогов
        if (!empty($cats)) {
            foreach ($cats as $catID) {
                /*                 * ****** формируем SEF ******* */
                $children_item_count = $this->help->getSubGroupGallery($catID);

                if ($children_item_count['amnt'] > 0) {
                    $siteURL = '/' . SEFU_PREFIX_GALLERY_URL . '/all/n/' . $catID . '/';
                } else {
                    $siteURL = '/' . SEFU_PREFIX_GALLERY_URL . '/view/n/' . $catID . '/';
                }

                $sefURL = $this->getSEFUGallery($catID); // формируем ЧПУ-урл

                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

                if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation, $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation, $sefURL);
                    }
                } else
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                        $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                /*                 * ****** формируем OLD ******* */

                $oldURL = $siteURL;

                $this->help->addOldURL($idSefSiteRelation, $oldURL);
            }
        }
    }

    /**
     * Метод applySEFUGallery формирует урлы галереи в формате SEFU.
     * Надо его отрефакторить.
     */
    public function applySEFUVideoGallery()
    {
        $cats = $this->help->getVideoGallery(); // достаем ID всех каталогов
        if (!empty($cats)) {
            foreach ($cats as $catID) {
                /*                 * ****** формируем SEF ******* */
                $children_item_count = $this->help->getSubVideGroupGallery($catID);

                if ($children_item_count['amnt'] > 0) {
                    $siteURL = '/' . SEFU_PREFIX_VIDEO_GALLERY_URL . '/all/n/' . $catID . '/';
                } else {
                    $siteURL = '/' . SEFU_PREFIX_VIDEO_GALLERY_URL . '/view/n/' . $catID . '/';
                }

                $sefURL = $this->getSEFUVideoGallery($catID); // формируем ЧПУ-урл

                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

                if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation,
                            $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                            $sefURL);
                    }
                } else
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                        $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                /*                 * ****** формируем OLD ******* */

                $oldURL = $siteURL;

                $this->help->addOldURL($idSefSiteRelation, $oldURL);
            }
        }
    }

    /**
     * Метод applySEFUCatalogueIndex формирует урлы каталога в формате SEFU. для индексной
     * Надо его отрефакторить.
     */
    public function applySEFUCatalogueIndex()
    {
        $siteURL = "/" . SEFU_PREFIX_CAT_URL_FOR_ADMIN . '/all/'; // получаем REALCATNAME

        $docId = $this->help->getDocIdByUrl($siteURL);
        $sefURL = $this->help->getDocRealCat($docId);

        if ($sefURL) {
            $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

            if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                    // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                    // то существующий записываем в старые, а полученный - на место существующего
                    $this->help->addOldURL($idSefSiteRelation,
                        $sefSiteRelationInfo['SEF_URL']);
                    $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                        $sefURL);
                }
            } else
                $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                    $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
            /*             * ****** формируем OLD ******* */

            $oldURL = $siteURL;

            $this->help->addOldURL($idSefSiteRelation, $oldURL);
        }
    }

    /**
     * Метод applySEFUCatalogue формирует урлы каталога в формате SEFU.
     * Надо его отрефакторить.
     */
    public function applySEFUCatalogue($catalogueId = null)
    {
        $cats = $this->help->getCats($catalogueId); // достаем ID всех каталогов
        if (!empty($cats)) {
            foreach ($cats as $catID) {
                /*                 * ****** формируем SEF ******* */
                $children_item_count = $this->help->getItemsCount($catID);

//          if ($children_item_count['amnt'] > 0) {
                $siteURL = "/" . SEFU_PREFIX_CAT_URL_FOR_ADMIN . '/view/n/' . $catID . '/';
//          } else {
//            $siteURL = "/" . SEFU_PREFIX_CAT_URL_FOR_ADMIN . $this->help->getRealURLCat($catID); // получаем REALCATNAME
//          }

                $sefURL = $this->getSEFUCat($catID); // формируем ЧПУ-урл

                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

                if ($idSefSiteRelation) { // если для узла каталога уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для узла каталога НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation, $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation, $sefURL);
                    } elseif ($sefURL == $sefSiteRelationInfo['SEF_URL']) {

                    }
                } else
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                        $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                /*                 * ****** формируем OLD ******* */

                if ($children_item_count['amnt'] > 0) {
                    $oldURL = $siteURL;
                } else {
                    $oldURL = $this->help->getCatCurURL($catID);
                }

                //          $oldURL = $this->help->getCatCurURL($catID);

                $this->help->addOldURL($idSefSiteRelation, $oldURL);
            }
        }
    }

    /**
     * Метод applySEFUItem формирует урлы карточек товара в формате SEFU
     *
     * @param int $itemId ID из таблицы ITEM - товар который перестраиваем
     */
    public function applySEFUItem($itemId = null)
    {
        $items = $this->help->getItems($itemId); // достаем ID всех карточек товара
        if (!empty($items)) {
            foreach ($items as $item) {
                $siteURL = "/" . SEFU_PREFIX_CAT_URL_FOR_ADMIN . '/item/n/' . $item['CATALOGUE_ID'] . '/it/' . $item['ITEM_ID'] . '/';

                $sefURL = $this->getSEFUItem($item['ITEM_ID']); // формируем ЧПУ-урл
                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);

                if (!empty($idSefSiteRelation)) { // если для новости уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для новости НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation, $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation, $sefURL);
                    }
                } else {
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL, $sefURL);
                }

                $oldURL = $siteURL;
                $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
            }
        }
    }

    /**
     * Метод applySEFUArticles формирует урлы статей в формате SEFU
     */
    public function applySEFUArticlesIndex()
    {
        $siteURL = "/" . SEFU_PREFIX_ARTICLE_URL . '/all/'; // получаем REALCATNAME

        $docId = $this->help->getDocIdByUrl($siteURL);
        $sefURL = $this->help->getDocRealCat($docId);

        if ($sefURL) {
            $idSefSiteRelation = $this->help->getIdSefSite($siteURL);
            if (!empty($idSefSiteRelation)) { // если для статьи уже есть ЧПУ
                $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                    // если уже существующий ЧПУ для статьи НЕ РАВЕН полученному,
                    // то существующий записываем в старые, а полученный - на место существующего
                    $this->help->addOldURL($idSefSiteRelation,
                        $sefSiteRelationInfo['SEF_URL']);
                    $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                        $sefURL);
                }
            } else
                $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                    $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
            /*             * ****** формируем OLD ******* */
            $oldURL = $siteURL; // старый урл соответствует урлу сайта, т.к. состоит из id статьи, а id изменяться не может
            $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
        }
    }

    /**
     * Метод applySEFUNews формирует урлы новостей в формате SEFU
     */
    public function applySEFUNews()
    {
        $news = $this->help->getNews(); // достаем ID всех новостей
        if (!empty($news)) {
            foreach ($news as $newsID) {
                /*                 * ****** формируем SEF ******* */
                $siteURL = "/" . SEFU_PREFIX_NEWS_URL . "/view/n/" . $newsID . "/"; // получаем урл сайта для новости
                $sefURL = $this->getSEFUNews($newsID); // формируем ЧПУ-урл для новости

                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);
                if (!empty($idSefSiteRelation)) { // если для новости уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для новости НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation,
                            $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                            $sefURL);
                    }
                } else
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                        $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                /*                 * ****** формируем OLD ******* */
                $oldURL = $siteURL; // старый урл соответствует урлу сайта, т.к. состоит из id новости, а id изменяться не может
                $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
            }
        }
    }

    /**
     * Метод applySEFUProjects формирует урлы новостей в формате SEFU
     */
    public function applySEFUProjects()
    {
        $news = $this->help->getProjects(); // достаем ID всех новостей
        if (!empty($news)) {
            foreach ($news as $projectsID) {
                /*                 * ****** формируем SEF ******* */
                $siteURL = "/" . SEFU_PREFIX_PROJECTS_URL . "/view/n/" . $projectsID . "/"; // получаем урл сайта для новости
                $sefURL = $this->getSEFUProjects($projectsID); // формируем ЧПУ-урл для проекта

                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);
                if (!empty($idSefSiteRelation)) { // если для новости уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для новости НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation,
                            $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                            $sefURL);
                    }
                } else {
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL, $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                }

                /*                 * ****** формируем OLD ******* */
                $oldURL = $siteURL; // старый урл соответствует урлу сайта, т.к. состоит из id новости, а id изменяться не может
                $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
            }
        }
    }

    /**
     * Метод applySEFUArticles формирует урлы статей в формате SEFU
     */
    public function applySEFUArticles()
    {
        $articles = $this->help->getArticles(); // достаем ID всех статей
        if (!empty($articles)) {
            foreach ($articles as $articleID) {
                /*                 * ****** формируем SEF ******* */
                $siteURL = "/" . SEFU_PREFIX_ARTICLE_URL_FOR_ADMIN . "/view/n/" . $articleID . "/"; // получаем урл сайта для статьи
                $sefURL = $this->getSEFUArticle($articleID); // формируем ЧПУ-урл для статьи
                $idSefSiteRelation = $this->help->getIdSefSite($siteURL);
                if (!empty($idSefSiteRelation)) { // если для статьи уже есть ЧПУ
                    $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);
                    if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                        // если уже существующий ЧПУ для статьи НЕ РАВЕН полученному,
                        // то существующий записываем в старые, а полученный - на место существующего
                        $this->help->addOldURL($idSefSiteRelation,
                            $sefSiteRelationInfo['SEF_URL']);
                        $this->help->updateSEF_SiteRelation($idSefSiteRelation,
                            $sefURL);
                    }
                } else
                    $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL,
                        $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
                /*                 * ****** формируем OLD ******* */
                $oldURL = $siteURL; // старый урл соответствует урлу сайта, т.к. состоит из id статьи, а id изменяться не может
                $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
            }
        }
    }

    /**
     * Метод applySEFUDoc формирует урлы страниц сайта в формате SEFU
     */
    public function applySEFUDoc()
    {
        $docs = $this->help->getDocs(); // достаем ID всех страниц сайта
        if (!empty($docs)) {
            foreach ($docs as $docID) {
                $this->_applySEFUDoc($docID);
            }
        }
    }

    public function _applySEFUDoc($docID)
    {
        // для страниц сайта действует условие: если в админке установлен URL, то с этой страницей ничего не делаем
        $info = $this->help->getDocInfo($docID);
        //          if(empty($info['URL'])){
        /*                 * ****** формируем SEF ******* */
        $siteURL = "/" . SEFU_PREFIX_DOC_URL . "/" . $docID; // получаем урл сайта
        $siteURL = !empty($info['URL']) ? $info['URL'] : $siteURL;

        $sefURL = $this->getSEFUDoc($docID); // формируем ЧПУ-урл
        //                    $idSefSiteRelation = $this->help->getIdSefSite(null,$sefURL);
        $idSefSiteRelation = $this->help->getIdSefSite($siteURL);
//        echo "$sefURL, $siteURL, $idSefSiteRelation<br/>";

        if (!empty($idSefSiteRelation)) { // если для страницы сайта уже есть ЧПУ
            $sefSiteRelationInfo = $this->help->getSEF_SiteRelationInfo($idSefSiteRelation);

            if ($sefURL != $sefSiteRelationInfo['SEF_URL']) {
                // если уже существующий ЧПУ для страницы сайта НЕ РАВЕН полученному,
                // то существующий записываем в старые, а полученный - на место существующего
                $this->help->addOldURL($idSefSiteRelation, $sefSiteRelationInfo['SEF_URL']);

                $this->help->updateSEF_SiteRelation($idSefSiteRelation, $sefURL);
            }

            if ($this->help->deleteOldNotNeedUrl($sefURL)) {

            }
        } else {
            $idSefSiteRelation = $this->help->saveSiteSEFRelation($siteURL, $sefURL); // сохраняем соответствие урла сайта -- ЧПУ-урлу
        }

//        $oldURL = $this->getDocCurURL($docID);
//        $this->help->addOldURL($idSefSiteRelation, $oldURL); // добавляет старый урл, если он еще не существует
    }

    /**
     * Возвращает ЧПУ-урл для каталога $catID
     * @param int $catID
     * @return string
     */
    public function getSEFUCat($catID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_CAT_URL_FOR_ADMIN . '/all/');
        $sefu_prefix_cat_url = $this->help->getDocRealCat($docId);
        $sefu_prefix_cat_url = (!empty($sefu_prefix_cat_url) && ($sefu_prefix_cat_url != '/')) ? $sefu_prefix_cat_url : '/' . SEFU_PREFIX_CAT_URL . '/';

        $latinURL = ltrim($this->help->getRealURLCat($catID), '/');

        //      $cyrURL = trim($this->help->getCatParentName($catID));
        //      $cyrURL = mb_strtolower($cyrURL,'utf-8');
        //      $cyrURL = preg_replace("/\s+/s", '-', $cyrURL);
        //      $translit = new Translit();
        //      $latinURL = $translit->getLatin($cyrURL);
        //      unset($translit);
        //      $latinURL = $sefu_prefix_cat_url.$latinURL."/";
        $latinURL = $sefu_prefix_cat_url . $latinURL;
        return $latinURL;
    }

    /**
     * Возвращает ЧПУ-урл для каталога $catID
     * @param int $catID
     * @return string
     */
    public function getSEFUGallery($catID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_GALLERY_URL . '/');
        $sefu_prefix_gallery_url = $this->help->getDocRealCat($docId);

        $sefu_prefix_gallery_url = (!empty($sefu_prefix_gallery_url) && ($sefu_prefix_gallery_url != '/')) ? $sefu_prefix_gallery_url : '/' . SEFU_PREFIX_GALLERY_URL . '/';

        $cyrURL = trim($this->help->getGalleryParentName($catID));
        $cyrURL = mb_strtolower($cyrURL, 'utf-8');
        $cyrURL = preg_replace("/\s+/s", '-', $cyrURL); // strtr не работает с пробелом на сервере gortorgsnab.ru (нет времени выяснять почему именно)
        // поэтому пробел заменяем сразу, независимо от правил транслитерации
        $translit = new Translit();
        $latinURL = $translit->getLatin($cyrURL);
        unset($translit);
        $latinURL = $sefu_prefix_gallery_url . $latinURL . "/";
        return $latinURL;
    }

    /**
     * Возвращает ЧПУ-урл для каталога $catID
     * @param int $catID
     * @return string
     */
    public function getSEFUVideoGallery($catID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_VIDEO_GALLERY_URL . '/');
        $sefu_prefix_video_gallery_url = $this->help->getDocRealCat($docId);

        $sefu_prefix_video_gallery_url = (!empty($sefu_prefix_video_gallery_url) && ($sefu_prefix_video_gallery_url != '/')) ? $sefu_prefix_video_gallery_url : '/' . SEFU_PREFIX_VIDEO_GALLERY_URL . '/';

        $cyrURL = trim($this->help->getVideoGalleryParentName($catID));
        $cyrURL = mb_strtolower($cyrURL, 'utf-8');
        $cyrURL = preg_replace("/\s+/s", '-', $cyrURL); // strtr не работает с пробелом на сервере gortorgsnab.ru (нет времени выяснять почему именно)
        // поэтому пробел заменяем сразу, независимо от правил транслитерации
        $translit = new Translit();
        $latinURL = $translit->getLatin($cyrURL);
        unset($translit);
        $latinURL = $sefu_prefix_video_gallery_url . $latinURL . "/";
        return $latinURL;
    }

    /**
     * Возвращает ЧПУ-урл для карточки товара $item
     * @param int $itemID
     * @return string
     */
    public function getSEFUItem($itemID)
    {
        $itemInfo = $this->help->getItemInfo($itemID); // извлекаем все данные о карточке товара

        $catURL = $this->getSEFUCat($itemInfo['CATALOGUE_ID']); // получаем ЧПУ-урл для каталога, которому принадлежит товар
        // Если специал урл пустой, то делаем транслит из имени товара
        if (empty($itemInfo['SPECIAL_URL'])) {
            $cyr = trim(mb_strtolower($itemInfo['NAME'], 'utf-8'));
            $translit = new Translit();
            $latinURL = $translit->getLatin($cyr);
            unset($translit);
            $itemURL = $catURL . $latinURL . "-{$itemID}/";
        } else {
            $itemURL = "{$catURL}{$itemInfo['SPECIAL_URL']}-{$itemID}/";
        }

        return $itemURL;
    }

    /**
     * Возвращает ЧПУ-урл для статьи с id $articleID
     * @param int $articleID
     * @return string
     */
    public function getSEFUArticle($articleID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_ARTICLE_URL . '/all/');
        $sefu_prefix_article_url = $this->help->getDocRealCat($docId);
        $sefu_prefix_article_url = (!empty($sefu_prefix_article_url) && ($sefu_prefix_article_url != '/')) ? $sefu_prefix_article_url : '/' . SEFU_PREFIX_ARTICLE_URL . '/';

        $title = trim($this->help->getNameArticle($articleID)); // получаем заголовок статьи
        $title = mb_strtolower($title, 'utf-8'); // переводим заголовок статьи в нижний регистр с учетом кодировки
        $title = preg_replace("/\s+/s", '-', $title); // strtr не работает с пробелом на сервере gortorgsnab.ru (нет времени выяснять почему именно)
        // поэтому пробел заменяем сразу, независимо от правил транслитерации
        $translit = new Translit();
        $latinTitle = $translit->getLatin($title); // получаем транслитерированный заголовок
        unset($translit);
        $sefUrl = $sefu_prefix_article_url . $latinTitle . "/"; // формируем полный урл
        return $sefUrl;
    }

    /**
     * Возвращает ЧПУ-урл для новости с id $newsID
     * @param int $newsID
     * @return string
     */
    public function getSEFUNews($newsID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_NEWS_URL . '/all/');
        $sefu_prefix_news_url = $this->help->getDocRealCat($docId);

        $sefu_prefix_news_url = (!empty($sefu_prefix_news_url) && ($sefu_prefix_news_url != '/')) ? $sefu_prefix_news_url : '/' . SEFU_PREFIX_NEWS_URL . '/';

        $info = $this->help->getNewsInfo($newsID); // получаем заголовок новости
        $title = trim(mb_strtolower($info['NAME'], 'utf-8')); // переводим заголовок новости в нижний регистр с учетом кодировки
        $title = preg_replace("/\s+/s", "-", $title); // strtr не работает с пробелом на сервере gortorgsnab.ru (нет времени выяснять почему именно)
        // поэтому пробел заменяем сразу, независимо от правил транслитерации
        $translit = new Translit();
        $latinTitle = $translit->getLatin($title); // получаем транслитерированный заголовок

        // полный урл новости состоит из '/news/' + транслит заголовка + '_' + дата публикации новости в формате yyyy-mm-dd
        $newsURL = $sefu_prefix_news_url . $latinTitle . "_" . $info['dateFormat'] . "/"; // формируем полный урл новости
        return $newsURL;
    }


    /**
     * Возвращает ЧПУ-урл для новости с id $newsID
     * @param int $projectsID
     *
     * @return string
     */
    public function getSEFUProjects($projectsID)
    {
        $docId = $this->help->getDocIdByUrl('/' . SEFU_PREFIX_PROJECTS_URL . '/index/');
        $sefu_prefix_url = $this->help->getDocRealCat($docId);

        $sefu_prefix_url = (!empty($sefu_prefix_url) && ($sefu_prefix_url != '/')) ? $sefu_prefix_url : '/' . SEFU_PREFIX_PROJECTS_URL . '/';

        $info = $this->help->getProjectInfo($projectsID); // получаем заголовок новости
        $title = trim(mb_strtolower($info['NAME'], 'utf-8')); // переводим заголовок новости в нижний регистр с учетом кодировки
        $title = preg_replace("/\s+/s", "-", $title); // strtr не работает с пробелом на сервере gortorgsnab.ru (нет времени выяснять почему именно)
        // поэтому пробел заменяем сразу, независимо от правил транслитерации
        $translit = new Translit();
        $latinTitle = $translit->getLatin($title); // получаем транслитерированный заголовок

        $newsURL = $sefu_prefix_url . $latinTitle . "/"; // формируем полный урл новости
        return $newsURL;
    }

    public function getSEFUDoc($docID)
    {
        $info = $this->help->getDocInfo($docID); // получаем массив со всей информацией о странице сайта
        //      if(empty($info['URL']) && $info['REALCATNAME'] != '/'){    // URL является главнейшим: если он есть, то ЧПУ === URL
        //        $docURL = $info['REALCATNAME'];
        //      }
        //      else $docURL = $info['URL'];

        $docURL = $info['REALCATNAME'];

        return $docURL;
    }

    /**
     * Возвращает урл сайта, соответствующий ЧПУ-урлу.
     * @param string $sefURL ЧПУ-урл
     * @return string урл сайта
     */
    public function getSiteURLbySEFU($sefURL)
    {
        return $this->help->getSiteURLbySEFU($sefURL);
    }

    /**
     * Возвращает ЧПУ-урл, соответствующий старому урлу.
     * @param string $oldURL старый урл
     * @return string ЧПУ-урл
     */
    public function getSefURLbyOldURL($oldURL)
    {
        return $this->help->getSefURLbyOldURL($oldURL);
    }

    /**
     * Возвращает текущий урл страницы сайта
     * @param int $docID
     * @return string
     */
    public function getDocCurURL($docID)
    {
        $docInfo = $this->help->getDocInfo($docID);
        // определение урла скопировано из another_pages.php
        if (empty($docInfo['URL']))
            $docInfo['URL'] = '/' . SEFU_PREFIX_DOC_URL . ($docInfo['REALCATNAME'] ? $docInfo['REALCATNAME'] : '/' . $docInfo['ANOTHER_PAGES_ID']) . '/';
        return $docInfo['URL'];
    }

}