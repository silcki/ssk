<?php
class ArticlesController extends Core_Controller_Action_Abstract
{
    protected function getSysText()
    {
        $textes = array(
            'all_articles',
            'page_main',
        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $docId = $this->AnotherPages->getPageId('/articles/');
        $articlePerPage = $this->getSettingValue('article_per_page') ? $this->getSettingValue('article_per_page') : 15;
        $articleHelper = $this->getServiceManager()->getHelper()->getArticles();

        $count = $articleHelper->getArticleCount();

        $page = $this->getParam('page', 1);

        $startSelect = ($page - 1) * $articlePerPage;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        $o_data['ap_id'] = $docId;
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($docId);
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $titleAdd = null;
        if ($page > 1) {
            $titleAdd = ', стр. '.$page;
        }

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocMeta($docId, $titleAdd)
            ->getDocPath($docId);

        $this->makeSectionInfo($count, $page, $articlePerPage, $o_data['file_name']);

        $articleHelper->getArticles(0, $startSelect, $articlePerPage);
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $articleId = $this->getParam('n');

        $docId = $this->AnotherPages->getPageId('/articles/');
        $parent_id = $this->AnotherPages->getDocParentId($docId);

        $_href = $this->AnotherPages->getSefURLbyOldURL('/articles/');
        $path = "//main_menu[url[text()='{$_href}']]";

        $menuHelper = new Core_Controller_Action_Helper_Menu($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $o_data['article_id'] = $articleId;
        $o_data['ap_id'] = $parent_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocPath($docId, array(), array('url'=>''));

        $this->getServiceManager()->getHelper()->getArticles()
            ->setParams($params)
            ->getArticleSingle($articleId)
            ->getMetaSingle($articleId);
    }
}