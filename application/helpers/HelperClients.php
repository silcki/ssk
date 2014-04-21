<?php
class HelperClients extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Clients
     */
    protected $clients;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->clients = $this->getServiceManager()->getModel()->getClients();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    public function getClients()
    {
        $clients = $this->clients->getClientsIndex($this->params['langId']);
        if ($clients) {
            $i_td = 0;
            foreach ($clients as $view) {
                if (($i_td > 0) && ($i_td % 3) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td > 0) && ($i_td % 12) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td % 12) == 0) {
                    $this->domXml->create_element('clients_li', '', 2);
                }

                if (($i_td % 3) == 0) {
                    $this->domXml->create_element('clients_td', '', 2);
                }

                $this->domXml->create_element('clients', '', 2);
                $this->domXml->set_attribute(array('client_id' => $view['CLIENT_ID']));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('email', $view['EMAIL']);
                $this->domXml->create_element('url', $view['URL']);
                $this->domXml->create_element('description', $view['DESCRIPTION']);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $doc = $this->anotherPages->getDocXml($view['CLIENT_ID'], 8, $this->params['langId']);
                if (!empty($doc)) {
                    $this->domXml->create_element('is_doc', 1);
                }

                $this->domXml->go_to_parent();

                $i_td++;
            }
        }
    }
} 