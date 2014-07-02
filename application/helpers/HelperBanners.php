<?php
class HelperBanners extends Core_Controller_Action_Helper_Abstract
{
    protected static $banners = array(
        'banner_image_map' => array('align' => 1, 'section' => 1, 'preg' => 1),
        'banner_header_slogan' => array('align' => 2, 'section' => 2, 'preg' => 1),
        'banner_header_phone1' => array('align' => 2, 'section' => 3, 'preg' => 1),
        'banner_header_phone2' => array('align' => 2, 'section' => 4, 'preg' => 1),
        'banner_header_schedule1' => array('align' => 2, 'section' => 17, 'preg' => 1),
        'banner_header_schedule2' => array('align' => 2, 'section' => 18, 'preg' => 1),
        'banner_header_address' => array('align' => 2, 'section' => 5, 'preg' => 1),

        'banner_footer_address' => array('align' => 3, 'section' => 6, 'preg' => 1),
        'banner_footer_copy' => array('align' => 3, 'section' => 7, 'preg' => 1),
        'banner_footer_slogan' => array('align' => 3, 'section' => 8, 'preg' => 1),

        'banner_java_scripts' => array('align' => 8, 'section' => 13, 'preg' => 1),

        'banner_left_side_menu' => array('align' => 9, 'section' => 14, 'preg' => 1),

        'banner_left_side' => array('align' => 10, 'section' => 15, 'preg' => 1),

        'index_under_news' => array('align' => 11, 'section' => 16, 'preg' => 0),

    );

    /**
     * @var SectionAlign
     */
    protected $sectionAlign;

    public function init()
    {
        $this->sectionAlign = $this->getServiceManager()->getModel()->getSectionAlign();
    }

    /**
     * Создать ущлы для всех баннеров
     */
    public function getBanners()
    {
        foreach (self::$banners as $block => $value) {
            $this->getBanner($block);
        }
    }

    /**
     * Создать узел для указанного банера
     *
     * @param string $block      имя блока
     * @param array  $customData кастомный блок
     */
    public function getBanner($block, $customData = array())
    {
        try {
            if (!empty($customData)) {
                $value = $customData;
            } else {
                $value = self::$banners[$block];
            }

        } catch (Exception $e) {
            throw new Exception('Banner block ' .$block. ' not found');
        }

        $banners = $this->sectionAlign->getBanners($value['align'], $value['section'], $this->params['langId']);

        if ($banners) {
            foreach ($banners as $banner) {
                $this->domXml->create_element($block, '', 1);
                $this->domXml->create_element('section_align_id', $banner['SECTION_ALIGN_ID']);

                $this->domXml->create_element('alt', $banner['ALT']);

                if ($value['preg'] == 1) {
                    $pattern = '/<p>(.*)<\/p>/Uis';
                    preg_match_all($pattern, $banner['DESCRIPTION'], $out);
                    $banner['DESCRIPTION'] = (isset($out[1][0]) && !empty($out[1][0])) ? $out[1][0] : $banner['DESCRIPTION'];
                }

                $this->setXmlNode($banner['DESCRIPTION'], 'description');

                if (!empty($banner['BANNER_CODE'])) {
                    $this->setXmlNode($banner['BANNER_CODE'], 'banner_code');
                }

                $this->domXml->create_element('type', $banner['TYPE']);
                $this->domXml->create_element('url', $banner['URL']);
                $this->domXml->create_element('newwin', $banner['NEWWIN']);
                $this->domXml->create_element('burl', $this->bannerURL($banner['URL']));

                if ($banner['IMAGE1'] != '' && strchr($banner['IMAGE1'], "#")) {
                    $image = $this->splitImageProperties($banner['IMAGE1']);
                    $this->domXml->create_element('image', '', 2);

                    $this->domXml->set_attribute(
                        array(
                            'src' => $image[0]['src'],
                            'w' => $image[0]['w'],
                            'h' => $image[0]['h']
                        )
                    );
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    /**
     * Метод для получения XML для баннерных мест
     *
     * @access public
     * @param  integer $lang
     * @param  string $currency
     *
     * @return $this
     */
    public function getBannerFromVar($block, $banner)
    {
        if ($banner) {
            $this->domXml->create_element($block, '', 1);
            $this->domXml->create_element('section_align_id',
                $banner['SECTION_ALIGN_ID']);

            $this->domXml->create_element('alt', $banner['ALT']);

            $pattern = '/<p>(.*)<\/p>/Uis';
            preg_match_all($pattern, $banner['DESCRIPTION'], $out);
            $banner['DESCRIPTION'] = (isset($out[1][0]) && !empty($out[1][0])) ? $out[1][0] : $banner['DESCRIPTION'];

            $this->setXmlNode($banner['DESCRIPTION'], 'description');

            $this->domXml->create_element('type', $banner['TYPE']);
            $this->domXml->create_element('url', $banner['URL']);
            $this->domXml->create_element('newwin', $banner['NEWWIN']);
            $this->domXml->create_element('burl',
                $this->bannerURL($banner['URL']));
            if ($banner['IMAGE1'] != '' && strchr($banner['IMAGE1'], "#")) {
                $image = $this->splitImageProperties($banner['IMAGE1']);
                $this->domXml->create_element('image', '', 2);

                $this->domXml->set_attribute(array('src' => $image[0]['src'],
                        'w' => $image[0]['w'],
                        'h' => $image[0]['h']
                    )
                );
                $this->domXml->go_to_parent();
            }

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * Получить URL банера
     *
     * @param string $url
     *
     * @return string
     */
    private function bannerURL($url)
    {
        $burl = '';

        if (!empty($url) || strchr($url, "http:")) {
            $burl = $url;
        } else {
            if (!empty($url)) {
                if (strchr($url, "doc")) {
                    if (substr($url, 0, 1) != "/") {
                        $burl .= "/";
                    }
                    $burl .= $url;
                }
                else {
                    if (substr($url, 0, 1) != "/") {
                        $burl = "/doc/" . $url;
                    } else {
                        $burl = "/doc" . $url;
                    }
                }
                if (substr($url, -1) != "/") {
                    $burl .="/";
                }
            } else {
                $burl = '';
            }
        }

        if (!empty($burl)) {
            $url = $burl;
        } else {
            $url = '';
        }

        return $url;
    }
} 