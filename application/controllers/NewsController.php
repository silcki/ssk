<?php
class NewsController extends Core_Controller_Action_Abstract
{
    public function preDispatch()
    {
        $docId = $this->AnotherPages->getPageId('/news/');

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->initPathIDs($docId);

        parent::preDispatch();
    }

    protected function getSysText()
    {
        $textes = array(
            'all_news',
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

        $docId = $this->AnotherPages->getPageId('/news/');
        $newsPerPage = $this->getSettingValue('news_per_page') ? $this->getSettingValue('news_per_page') : 15;
        $newsHelper = $this->getServiceManager()->getHelper()->getNews();

        $count = $newsHelper->getNewsCount();

        $page = $this->getParam('page', 1);

        $startSelect = ($page - 1) * $newsPerPage;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        $o_data['news_id'] = 0;
        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($docId);

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

        $this->makeSectionInfo($count, $page, $newsPerPage, $o_data['file_name']);

        $newsHelper->getNews(0, $startSelect, $newsPerPage);
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $newsId = $this->getParam('n');

        $docId = $this->AnotherPages->getPageId('/news/');
        $parentId = $this->AnotherPages->getDocParentId($docId);

        $_href = $this->AnotherPages->getSefURLbyOldURL('/news/');

        $path = "//main_menu[url[text()='{$_href}']]";

        $menuHelper = new Core_Controller_Action_Helper_Menu($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $o_data['news_id'] = $newsId;
        $o_data['ap_id'] = $parentId;
        $o_data['is_vote'] = '';

        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocPath($docId, array(), array(array('url'=>'', 'name'=>'')));

        $this->getServiceManager()->getHelper()->getNews()
            ->setParams($params)
            ->getNewsSingle($newsId)
            ->getMetaSingle($newsId);
    }
}