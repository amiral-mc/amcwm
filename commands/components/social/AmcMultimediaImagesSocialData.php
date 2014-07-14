<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsSocialData class,  gets articles as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcMultimediaImagesSocialData extends AmcSocialData {

    /**
     *
     * @var string current route 
     */
    protected $route = "multimedia/images/view";

    /**
     *
     * Post content to social network
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function post() {
        Yii::import("amcwm.modules.multimedia.components.*");
        $list = new MediaListData(null, SiteData::IAMGE_TYPE, 60 * 60 * 24, $this->limit);
        $list->setLanguage($this->language);
        $list->addJoin("inner join module_social_config c on t.image_id = c.ref_id");
        $list->addJoin(sprintf("left join module_social_config_langs lc on c.config_id = lc.config_id and lc.content_lang = %s", AmcWm::app()->db->quoteValue($this->language)));
        $list->addWhere("(post_date is null or lc.config_id is null) and module_id = {$this->moduleId} and table_id = 2");
        $list->setAutoGenerate(false);
        $list->addColumn('is_background');
        $list->addColumn("creation_date", 'create_date');
        $list->addColumn("c.config_id", "config_id");
        $list->addOrder("creation_date asc");
        $list->generate();
        $multimediaDataset = $list->getQuery()->queryAll();
        $mediaPaths = MediaListData::getSettings()->mediaPaths;
        foreach ($multimediaDataset as $multimedia) {
            if ($multimedia['is_background']) {
                $mediaPath = $mediaPaths['backgrounds']['path'] . "/{$multimedia['item_id']}.{$multimedia['ext']}";
            } else {
                $mediaPath = $mediaPaths['images']['path'] . "/{$multimedia['item_id']}.{$multimedia['ext']}";
            }
            $mediaPath = str_replace("{gallery_id}", $multimedia['gallery_id'], $mediaPath);            
            $mediaFile = Yii::app()->baseUrl . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mediaPath);
            $mediaUrl = Yii::app()->params['siteUrl'] . DIRECTORY_SEPARATOR . $mediaPath;
            if (is_file($mediaFile)) {
                $data['type'] = 'image';
                $data['data']['header'] = $multimedia['title'];
                $data['data']['image'] = $mediaUrl;
                $data['data']['link'] = $this->createUrl($this->route, array('id' => $multimedia['item_id'], 'lang' => $this->language, 'title' => $multimedia['title']));
                $this->updateSoicalConfig(2, $multimedia['item_id'], $multimedia['create_date'], $multimedia['config_id']);
                $this->social->postData($data);
            }
        }
    }

}
