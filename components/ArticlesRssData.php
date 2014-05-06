<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsTickerRSS class,  Gets the contents "articles / videos / images" to displayed in rss feeds from news
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesRssData extends RssSiteData {

    /**
     * Is articles type breaking or not
     * @var boolean
     */
    protected $isBreaking = false;

    /**
     * Set articles type to breaking or not
     * @param boolean $isBreaking 
     * return void
     */
    public function setIsBreaking($isBreaking) {
        $this->isBreaking = $isBreaking;
    }

    /**
     *
     * Sets the the ArticlesListData.items array      
     * @param array $articles 
     * @access protected     
     * @return void
     */
    protected function setDataset($articles) {
        $this->setMediaPath(Yii::app()->baseUrl . "/" . ArticlesListData::getSettings()->mediaPaths['images']['path'] . "/");
        $index = -1;
        foreach ($articles As $article) {
            if ($this->recordIdAsKey) {
                $index = $article['article_id'];
            } else {
                $index++;
            }
            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($article["article_header"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $article["article_header"];
            }
            $this->items[$index]['id'] = $article["article_id"];
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), array('id' => $article['article_id'], 'title' => $article["article_header"]));
            $image = "";
            if (file_exists(Yii::app()->basePath . "/../.." . $this->mediaPath . $article["article_id"] . "." . $article["thumb"]) && $this->storyType != self::HEADING_STORY) {
                $imageSrc = Yii::app()->request->getHostInfo() . $this->mediaPath . $article["article_id"] . "." . $article["thumb"];
                $image = CHtml::image($imageSrc, "", array("width" => "150", "border" => "0"));
            }
            $this->items[$index]['type'] = $this->type;
            $this->items[$index]['image'] = $image;            
            
            $image = null;
            switch ($this->storyType) {
                case self::HEADING_STORY:
                    $this->items[$index]['article_detail'] = null;
                    break;
                case self::FULL_STORY:                   
                    $article['article_detail'] = strip_tags($article['article_detail'], "<br /><br><p><b><img><a><li><ul><ol>");
                    if ($image) {
                        $image = '<div>';
                        $image .='<div><img src="' . $imageSrc . '" /></div>';
                        $image .='</div>';
                    }
                    $article['article_detail'] = $image . $article['article_detail'];
                    $article['article_detail'] .= Html::link(AmcWm::t("amcFront", 'Company Name'), Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl) . " ";
                    $article['article_detail'] = strip_tags($article['article_detail'], "<br /><br><p><b><img><a><li><ul><ol>");
                    break;
                case self::SHORT_STORY:                 
                    $article['article_detail'] = strip_tags($article['article_detail'], "<br /><br><p><b><img><a><li><ul><ol>");
                    $article['article_detail'] = $image . " " . Html::utfSubstring($article['article_detail'], 0, 400);
                    break;
            }
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $article[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

    /**
     * Generate the articles list array, each article is associated  array that contain's following items:
     * <ul>
     * <li>title: string, article title</li>
     * <li>image: string, link for article image</li>
     * <li>link: string, link for displaying article details</li>
     * <li>publish_date: string, link for displaying article publish date</li>     
     * </ul>
     * @access public
     * @return void
     */
    public function generate() {
        if ($this->storyType != self::HEADING_STORY) {
            $this->addColumn("article_detail");
            $this->addColumn("image_description");
        }
        $this->addColumn("publish_date");
        foreach ($this->tables as $table) {
            if ($this->isBreaking && $table = "news") {
                $this->addWhere("news.is_breaking = 1");
                break;
            }
        }
        parent::generate();
    }

}