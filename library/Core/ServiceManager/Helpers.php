<?php
class Core_ServiceManager_Helpers extends Core_ServiceManager_Abstract
{
    /**
     * Получить помощника
     *
     * @param string $helperName имя помощника
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getHelper($helperName)
    {
        $name = 'helpers_' . strtolower($helperName);

        if (!$this->has($name)) {
            return $this->storage[$name];
        }

        throw new Exception('Helper '. $helperName . ' not found');
    }

    /**
     * Получить помощника HelperAnotherPages
     *
     * @return HelperAnotherPages
     */
    public function getAnotherPages()
    {
        return $this->getHelper('HelperAnotherPages');
    }

    /**
     * Получить помощника HelperArticles
     *
     * @return HelperArticles
     */
    public function getArticles()
    {
        return $this->getHelper('HelperArticles');
    }

    /**
     * Получить помощника HelperBanners
     *
     * @return HelperBanners
     */
    public function getBanners()
    {
        return $this->getHelper('HelperBanners');
    }

    /**
     * Получить помощника HelperCatalogue
     *
     * @return HelperCatalogue
     */
    public function getCatalogue()
    {
        return $this->getHelper('HelperCatalogue');
    }

    /**
     * Получить помощника HelperClients
     *
     * @return HelperClients
     */
    public function getClients()
    {
        return $this->getHelper('HelperClients');
    }

    /**
     * Получить помощника HelperFileTypes
     *
     * @return HelperFileTypes
     */
    public function getFileTypes()
    {
        return $this->getHelper('HelperFileTypes');
    }

    /**
     * Получить помощника HelperLanguages
     *
     * @return HelperLanguages
     */
    public function getLanguages()
    {
        return $this->getHelper('HelperLanguages');
    }

    /**
     * Получить помощника HelperNews
     *
     * @return HelperNews
     */
    public function getNews()
    {
        return $this->getHelper('HelperNews');
    }

    /**
     * Получить помощника HelperSystemTextes
     *
     * @return HelperSystemTextes
     */
    public function getSystemTextes()
    {
        return $this->getHelper('HelperSystemTextes');
    }

    /**
     * Получить помощника HelperVopros
     *
     * @return HelperVopros
     */
    public function getVopros()
    {
        return $this->getHelper('HelperVopros');
    }
} 