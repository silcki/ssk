<?php
class HelperFaq extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Faq
     */
    protected $faq;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->faq = $this->getServiceManager()->getModel()->getFaq();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    /**
     * @return $this
     */
    public function getMessages()
    {
        $this->domXml->set_tag('//data', true);
        $messages_group = $this->faq->getGroupMessage($this->params['langId']);
        if (!empty($messages_group)) {
            foreach ($messages_group as $view) {
                $this->domXml->create_element('faq_group', '', 2);
                $this->domXml->set_attribute(array('id' => $view['QUESTION_GROUP_ID']));

                $this->domXml->create_element('name', $view['NAME']);

                $messages = $this->faq->getMessage($view['QUESTION_GROUP_ID'], $this->params['langId']);
                if (!empty($messages)) {
                    foreach ($messages as $view) {
                        $this->domXml->create_element('faq', '', 2);
                        $this->domXml->set_attribute(array('question_id' => $view['QUESTION_ID']));

                        $this->domXml->create_element('question', $view['QUESTION']);
                        $this->setXmlNode($view['ANSWER'], 'answer');

                        $this->domXml->go_to_parent();
                    }
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 