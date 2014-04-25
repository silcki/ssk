<?php
class HelperProjects extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Projects
     */
    protected $projects;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->projects = $this->getServiceManager()->getModel()->getProjects();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    /**
     * @param $projectId
     *
     * @return int
     */
    public function getProjectsCount($projectId)
    {
        return $this->projects->getProjectsCount($projectId);
    }

    /**
     * @param $projectId
     *
     * @return $this
     */
    public function getMetaSingle($projectId)
    {
        $news = $this->projects->getProjectsSingle($projectId, $this->params['langId']);
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
     * Метод для получения XML подробного текста новости
     * @access   public
     * @return   $this
     */
    public function getProjectsSingle($projectId)
    {
        $news = $this->projects->getProjectsSingle($projectId, $this->params['langId']);
        if (!empty($news)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('projects_single', '', 2);
            $this->domXml->set_attribute(array('news_id' => $news['PROJECTS_ID']));

            $this->domXml->create_element('name', $news['NAME']);
            $this->domXml->create_element('date', $news['date']);

            $this->getDocXml($projectId, 11, true, $this->params['langId']);
            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @param int $startSelect
     * @param int $per_page
     *
     * @return $this
     */
    public function getProjects($startSelect = 0, $per_page = 0)
    {
        $projects = $this->projects->getProjects($this->params['langId'], $startSelect, $per_page);

        if ($projects) {
            $lang = ($this->params['langId'] > 0) ?  '/'.$this->params['lang']:'';

            foreach ($projects as $news_view) {
                $is_lang = true;
                $href = '/projects/view/n/' . $news_view['PROJECTS_ID'] . '/';

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('projects', '', 2);
                $this->domXml->set_attribute(array('news_id' => $news_view['PROJECTS_ID']
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
} 