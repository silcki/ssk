<?php
class ProjectsController extends Core_Controller_Action_Abstract
{
    protected function _getSysText()
    {
        $textes = array(
            'page_main',
            'all_projects',
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

        $projectId = $this->_getParam('n');

        $projectsHelper = $this->getServiceManager()->getHelper()->getProjects()
                                                ->setParams($params);
        
        $docId = $this->AnotherPages->getPageId('/projects/');
        $perPage = $this->getSettingValue('projects_per_page') ? $this->getSettingValue('projects_per_page') : 15;
        $count = $projectsHelper->getProjectsCount($projectId);

        $page = $this->getParam('page', 1);

        $startSelect = ($page - 1) * $perPage;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        $o_data['project_id'] = $projectId;
        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($docId);
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);


        $projectsHelper->getProjects($startSelect, $perPage);

        $this->makeSectionInfo($count, $page, $perPage, $o_data['file_name']);
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $projectId = $this->_getParam('n');

        $docId = $this->AnotherPages->getPageId('/projects/index/');

        $o_data['project_id'] = $projectId;
        $o_data['is_vote'] = '';

        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId, array(), array('url'=>''));

        $this->getServiceManager()->getHelper()->getProjects()
            ->setParams($params)
            ->getMetaSingle($projectId)
            ->getProjectsSingle($projectId);
    }
}
