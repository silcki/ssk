<?php
class Core_ServiceManager_Models extends Core_ServiceManager_Abstract
{
    /**
     * Получить модель
     *
     * @param string $modelName имя модели
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getModel($modelName)
    {
        $name = 'models_' . strtolower($modelName);

        if (!$this->has($name)) {
            return $this->storage[$name];
        }

        throw new Exception('Model '. $modelName . ' not found');
    }

    /**
     * Получить модель AnotherPages
     *
     * @return AnotherPages
     */
    public function getAnotherPages()
    {
        return $this->getModel('AnotherPages');
    }

    /**
     * Получить модель Announcement
     *
     * @return Announcement
     */
    public function getAnnouncement()
    {
        return $this->getModel('Announcement');
    }

    /**
     * Получить модель Article
     *
     * @return Article
     */
    public function getArticle()
    {
        return $this->getModel('Article');
    }

    /**
     * Получить модель Booklets
     *
     * @return Booklets
     */
    public function getBooklets()
    {
        return $this->getModel('Booklets');
    }

    /**
     * Получить модель Catalogue
     *
     * @return Catalogue
     */
    public function getCatalogue()
    {
        return $this->getModel('Catalogue');
    }

    /**
     * Получить модель Clients
     *
     * @return Clients
     */
    public function getClients()
    {
        return $this->getModel('Clients');
    }

    /**
     * Получить модель Faq
     *
     * @return Faq
     */
    public function getFaq()
    {
        return $this->getModel('Faq');
    }

    /**
     * Получить модель FileTypes
     *
     * @return FileTypes
     */
    public function getFileTypes()
    {
        return $this->getModel('FileTypes');
    }

    /**
     * Получить модель Gallery
     *
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->getModel('Gallery');
    }

    /**
     * Получить модель Guestbook
     *
     * @return Guestbook
     */
    public function getGuestbook()
    {
        return $this->getModel('Guestbook');
    }

    /**
     * Получить модель News
     *
     * @return News
     */
    public function getNews()
    {
        return $this->getModel('News');
    }

    /**
     * Получить модель Projects
     *
     * @return Projects
     */
    public function getProjects()
    {
        return $this->getModel('Projects');
    }

    /**
     * Получить модель SectionAlign
     *
     * @return SectionAlign
     */
    public function getSectionAlign()
    {
        return $this->getModel('SectionAlign');
    }

    /**
     * Получить модель SystemSets
     *
     * @return SystemSets
     */
    public function getSystemSets()
    {
        return $this->getModel('SystemSets');
    }

    /**
     * Получить модель Textes
     *
     * @return Textes
     */
    public function getTextes()
    {
        return $this->getModel('Textes');
    }

    /**
     * Получить модель Vopros
     *
     * @return Vopros
     */
    public function getVopros()
    {
        return $this->getModel('Vopros');
    }
}