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
class AmcBaseArticlesSocialData extends AmcSocialData {

    protected $table = "articles";


    /**
     *
     * @var string current route 
     */
    protected $route = 'articles/default/view';

    /**
     *
     * Post content to social network
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function post() {
        $list = new ArticlesListData(array($this->table), 60 * 60 * 24, $this->limit);
        $list->setLanguage($this->language);
        $list->setDateCompareField("publish_date");
        $list->addJoin("inner join module_social_config c on t.article_id = c.ref_id");
        $list->addJoin(sprintf("left join module_social_config_langs lc on c.config_id = lc.config_id and lc.content_lang = %s", AmcWm::app()->db->quoteValue($this->language)));
        $list->addWhere("(post_date is null or lc.config_id is null) and module_id = {$this->moduleId} and table_id = 1");
        $list->setAutoGenerate(false);
        $list->addColumn("c.config_id", "config_id");
        $list->addColumn("create_date");
        $list->addOrder("create_date asc");
        $list->generate();
        //echo $list->getCount();
        $articles = $list->getQuery()->queryAll();
        foreach ($articles as $article) {
            $data['type'] = 'text';
            $data['data']['details'] = NULL;
            $data['data']['header'] = $article['article_header'];
            $data['data']['image'] = null;
            if ($article['thumb']) {
                $mediaPath = ArticlesListData::getSettings()->mediaPaths['images']['path'] . "/" . $article['article_id'] . "." . $article['thumb'];
                $mediaFile = Yii::app()->baseUrl . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $mediaPath);
                if (is_file($mediaFile)) {
                    $data['data']['image'] = Yii::app()->params['siteUrl'] . '/' . $mediaPath;
                }
            }
            $data['data']['link'] = $this->createUrl($this->route, array('id' => $article['article_id'], 'lang' => $this->language, 'title' => $article['article_header']));
            $isConfig = AmcWm::app()->db->createCommand("select config_id from module_social_config_langs where config_id = {$article['config_id']} and content_lang = " . AmcWm::app()->db->quoteValue($this->language))->queryScalar();
            $query = "updated module_social_config set post_date = '{$article['create_date']}' where module_id = {$this->moduleId} and table_id = 1 and ref_id = {$article['article_id']}";
            if (!$isConfig) {
                AmcWm::app()->db->createCommand("insert into module_social_config_langs (config_id ,content_lang) values({$article['config_id']}, " . AmcWm::app()->db->quoteValue($this->language) . ")")->execute();
            }
            $query = "update module_social_config set post_date = '{$article['create_date']}' where module_id = {$this->moduleId} and table_id = 1 and ref_id = {$article['article_id']}";
            AmcWm::app()->db->createCommand($query)->execute();
            $this->updateSoicalConfig(1, $article['article_id'], $article['create_date'], $article['config_id']);
            $this->social->postData($data);
        }
    }

}
