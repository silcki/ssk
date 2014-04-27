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

    /**
     * @return $this
     */
    public function getIndexClients()
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

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getClients()
    {
        $clients = $this->clients->getClients($this->params['langId']);
        if ($clients) {
            $i_td = 0;
            foreach ($clients as $view) {
                if (($i_td > 0) && ($i_td % 4) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td % 4) == 0) {
                    $this->domXml->create_element('clients_tr', '', 2);
                }

                $this->domXml->create_element('clients', '', 2);
                $this->domXml->set_attribute(array('client_id' => $view['CLIENT_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('email', $view['EMAIL']);
                $this->domXml->create_element('url', $view['URL']);
                $this->domXml->create_element('description', $view['DESCRIPTION']);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
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

        return $this;
    }

    /**
     * @param $clientId
     *
     * @return $this
     */
    public function getClientData($clientId)
    {
        $this->domXml->create_element('client_data', '', 2);
        $this->getDocXml($clientId, 8, true, $this->params['langId']);

        return $this;
    }

    /**
     * @param $clientId
     *
     * @return $this
     */
    public function getClientMeta($clientId)
    {
        $info = $this->clients->getClientsInfo($clientId, $this->params['langId']);
        if ($info) {
            $this->domXml->create_element('doc_meta', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['NAME']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @param array $countryIds
     *
     * @return $this
     */
    public function getCountry($countryIds = array())
    {
        $country = $this->clients->getCountry();
        if (!empty($country)) {
            foreach ($country as $view) {
                $this->domXml->create_element('client_country', '', 2);
                $this->domXml->set_attribute(
                    array(
                    'id' => $view['COUNTRY_ID'],
                    'active' => in_array($view['COUNTRY_ID'], $countryIds) ? 1:0
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @param array $scopeIds
     *
     * @return $this
     */
    public function getScope($scopeIds = array())
    {
        $scope = $this->clients->getScope();
        if (!empty($scope)) {
            foreach ($scope as $view) {
                $this->domXml->create_element('client_scope', '', 2);
                $this->domXml->set_attribute(
                    array(
                        'id' => $view['SCOPE_ID'],
                        'active' => in_array($view['SCOPE_ID'], $scopeIds) ? 1:0
                    ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @param array $productTypeIds
     *
     * @return $this
     */
    public function getProductType($productTypeIds = array())
    {
        $productType = $this->clients->getProductType();
        if (!empty($productType)) {
            foreach ($productType as $view) {
                $this->domXml->create_element('client_product_type', '', 2);
                $this->domXml->set_attribute(
                    array(
                        'id' => $view['PRODUCT_TYPE_ID'],
                        'active' => in_array($view['PRODUCT_TYPE_ID'], $productTypeIds) ? 1:0
                    ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 