<?php
class HelperFileTypes extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var FileTypes
     */
    protected $fileTypes;

    public function init()
    {
        $this->fileTypes = $this->getServiceManager()->getModel()->getFileTypes();
    }

    public function getFileTypes()
    {
        $types = $this->fileTypes->getFileTypes();
        if (!empty($types)) {
            foreach ($types as $view) {
                $this->domXml->create_element('file_types', '', 2);
                $this->domXml->set_attribute(array('type' => $view['EXT']
                ));

                $href = '';
                if (!empty($view['IMAGE1'])) {
                    $_temp = explode('#', $view['IMAGE1']);
                    $href = '/images/filetypes/' . $_temp[0];
                }

                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }
} 