<?php
class SitemapController extends Core_Controller_Action_Abstract
{
    protected function _getSysText()
    {
        $textes = array(
            'sitemap_another_pages',
            'sitemap_news',
            'sitemap_articles',
            'sitemap_gallary',
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

        $docId = $this->AnotherPages->getPageId('/sitemap/');

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getSitemap()
             ->getGalleryGroup(0, 1)
             ->getVideoGalleryGroup(0, 1)
             ->getNews()
             ->getArticles();
    }
}