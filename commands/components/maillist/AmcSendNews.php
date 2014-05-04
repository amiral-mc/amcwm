<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Base class for all newsletter send mail class
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
class AmcSendNews extends AmcMaillist {

    /**
     * @var intger breaking news limit  
     */
    protected $breakingLimit = 3;

    /**
     * @var intger top articles limit  
     */
    protected $topLimit = 6;

    /**
     * @var intger articles limit  
     */
    protected $articlesLimit = 10;

    /**
     * Set the message data
     */
    protected function setMessage() {
        $articlesRoute = 'maillist/default/article';
        $settings = ArticlesListData::getSettings();
        $options = $settings->getOptions();
        $defaultImage = str_replace(AmcWm::app()->request->baseUrl, AmcWm::app()->params['siteUrl'], $options['news']['default']['noImageListing']);
        if (isset($this->extraOptions['articlesLimit'])) {
            $this->articlesLimit = $this->extraOptions['articlesLimit'];
        }
        if (isset($this->extraOptions['topLimit'])) {
            $this->topLimit = $this->extraOptions['topLimit'];
        }
        $cmdQuery = sprintf("
                select m.section_id, st.section_name from maillist_messages_setions m
                inner join sections s on m.section_id = s.section_id
                inner join sections_translation st on s.section_id = st.section_id and content_lang =%s 
                where published = 1 and m.message_id = %d and s.parent_section is null order by section_sort"
                , Yii::app()->db->quoteValue($this->language)
                , $this->msgDataset['message_id']
        );
        $sectionsDataset = Yii::app()->db->createCommand($cmdQuery)->queryAll();
        if (!$sectionsDataset) {
            $cmdQuery = sprintf("
                select s.section_id, st.section_name from sections s
                inner join sections_translation st on s.section_id = st.section_id and content_lang =%s 
                where published = 1 and s.parent_section is null order by section_sort"
                    , Yii::app()->db->quoteValue($this->language)
            );
            $sectionsDataset = Yii::app()->db->createCommand($cmdQuery)->queryAll();
        }

        $topList = new ArticlesListData(array('news'), 0, $this->topLimit);
        $topList->setMediaPath($settings->mediaPaths['newsList']['path'] . "/");

        $topList->setLanguage($this->language);
        $topList->addOrder('create_date desc');
        $topList->addWhere('is_breaking = 0');
        $topList->addColumn('create_date');
        $topList->generate();
        $this->send = false;
        $templateVars = array();
        $topArticles = $topList->getItems();
        $firstTopArticle = array_shift($topArticles);
        $firstTopArticleUrl = $this->createUrl($articlesRoute, array('id' => $firstTopArticle['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'], 'e' => '__user__'));
        if ($firstTopArticle['imageExt']) {
            $templateVars['firstTopArticleImageUrl'] = AmcWm::app()->params['siteUrl'] . '/' . $firstTopArticle['image'];
        } else {
            $templateVars['firstTopArticleImageUrl'] = $defaultImage;
        }
        $templateVars['firstTopArticle'] = $firstTopArticle['title'];
        $templateVars['firstTopArticleUrl'] = $firstTopArticleUrl;
        $templateVars['firstTopArticleDate'] = date("Y-m-d H:i:s", strtotime($firstTopArticle['create_date']));


        $templateVars['articlesList'] = "";
        $templateVars['topList'] = "";
        $templateVars['breakingNews'] = "";

        $sectionsData = array();
        $sections = array();
        $i = 0;
        foreach ($sectionsDataset as $sectionIndex => $section) {
            $sections[] = $section['section_id'];
            $articleList = new ArticlesListData(array('news'), 0, $this->articlesLimit);
            $articleList->addColumn('create_date');
            $articleList->setMediaPath($settings->mediaPaths['newsList']['path'] . "/");
            $articleList->setLanguage($this->language);
            $articleList->subSectionsInUse(true);
            $articleList->addOrder('create_date desc');
            $articleList->addWhere('is_breaking = 0');
            $articleList->setSectionId($section['section_id']);
            $articleList->generate();
            if ($articleList->getCount()) {
                $sectionArticles = $articleList->getItems();
                $firstSectionArticle = array_shift($sectionArticles);
                $sectionsData[$i]['sectionTopArticleUrl'] = $this->createUrl($articlesRoute, array('id' => $firstSectionArticle['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'], 'e' => '__user__'));
                if ($firstSectionArticle['imageExt']) {
                    $sectionsData[$i]['sectionTopArticleImageUrl'] = AmcWm::app()->params['siteUrl'] . '/' . $firstSectionArticle['image'];
                } else {
                    $sectionsData[$i]['sectionTopArticleImageUrl'] = $defaultImage;
                }
                $sectionsData[$i]['sectionTopArticle'] = $firstSectionArticle['title'];
                $sectionsData[$i]['sectionTopArticleDate'] = date("Y-m-d H:i:s", strtotime($firstSectionArticle['create_date']));
                $sectionsData[$i]['section'] = $section['section_name'];
                $sectionsData[$i]['articlesList'] = array();
                $j = 0;
                foreach ($sectionArticles as $sectionArticle) {
                    $sectionsData[$i]['articlesList'][$j]['articleUrl'] = $this->createUrl($articlesRoute, array('id' => $sectionArticle['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'], 'e' => '__user__'));
                    $sectionsData[$i]['articlesList'][$j]['article'] = $sectionArticle['title'];
                    $sectionsData[$i]['articlesList'][$j]['articleDate'] = date("Y-m-d H:i:s", strtotime($sectionArticle['create_date']));
                    $j++;
                }
                if (!$this->send) {
                    $this->send = true;
                }
                $i++;
            }
        }
        $templateLoops = array();
        $templateLoops['sectionsList'] = $sectionsData;
        $templateLoops['breakingList'] = array();
        $this->send = count($sectionsDataset);
        $breaking = new TickerData(array('news'), $this->breakingLimit, true, $this->language, array(), $sections);
        $breakingDataset = $breaking->getArticles()->getItems();
        $i = 0;
        foreach ($breakingDataset as $breakingRow) {
            $templateLoops['breakingList'][$i]['breakingArticle'] = $breakingRow['title'];
            $i++;
        }
        $template = file_get_contents(AmcWm::app()->basePath . "/../{$this->templateFolder}/template.html");

        $topArticlesData = array();
        $i = 0;

        foreach ($topArticles as $topArticle) {
            if ($topArticle['imageExt']) {
                $topArticlesData[$i]['topArticleImage'] = AmcWm::app()->params['siteUrl'] . '/' . $topArticle['image'];
            } else {
                $topArticlesData[$i]['topArticleImage'] = $defaultImage;
            }
            $topArticlesData[$i]['topArticle'] = $topArticle['title'];
            $topArticlesData[$i]['topArticleUrl'] = $this->createUrl($articlesRoute, array('id' => $topArticle['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'], 'e' => '__user__'));
            $i++;
        }
        $templateLoops['topArticles'] = $topArticlesData;
        $templateVars['templateBaseUrl'] = AmcWm::app()->params['siteUrl'] . "/" . self::$settings['media']['paths']['templates']['path'] . "";
        $templateVars['templateUrl'] = AmcWm::app()->params['siteUrl'] . "/{$this->templateFolder}";
        $templateVars['siteUrl'] = AmcWm::app()->params['siteUrl'];
        $templateVars['lang'] = $this->language;
        $templateVars['link'] = $this->createUrl("maillist/default/view", array('list' => $this->currentTime, 'e' => '__user__', 'lang' => $this->language, 'm' => $this->msgDataset['message_id']));        
        $templateVars['log'] = '__log__';
        $templateVars['unsubscribeUrl'] = $this->createUrl("maillist/default/unsubscribe");
        $templateVars['rssUrl'] = $this->createUrl("rss/default/index");
        $templateVars['contactUs'] = $this->createUrl("site/contact/");
        $templateVars['privacyPolicy'] = $this->createUrl("site/privacy/");


        $templateVars['copyright'] = AmcWm::t("maillist", "_copyright_ {year}", array('{year}' => date('Y')), null, $this->language);
        if ($this->language == 'ar') {
            $templateVars['direction'] = "rtl";
            $templateVars['align'] = "right";
            $direction = "rtl";
            $align = "right";
        } else {
            $templateVars['direction'] = "ltr";
            $templateVars['align'] = "left";
        }
        $template = $this->replaceTemplateVars($template, $templateVars);
        $templateText = array();
        $templateText['link'] = AmcWm::t("maillist", "To view it in your web-browser click here");
        $templateText['Newsletter'] = AmcWm::t("maillist", "Newsletter", array(), null, $this->language);
        $templateText['AD'] = AmcWm::t("maillist", "AD", array(), null, $this->language);
        $templateText['Breaking News'] = AmcWm::t("maillist", "Breaking News", array(), null, $this->language);
        $templateText['Follow Us'] = AmcWm::t("maillist", "Follow Us", array(), null, $this->language);
        $templateText['You have received this message because of your relationship with us as a newsletter'] = AmcWm::t("maillist", "You have received this message because of your relationship with us as a newsletter", array(), null, $this->language);
        $templateText['Unsubscribe'] = AmcWm::t("maillist", "Unsubscribe", array(), null, $this->language);
        $templateText['Contact Us'] = AmcWm::t("maillist", "Contact Us", array(), null, $this->language);
        $templateText['Service Conditions And Privacy Policy'] = AmcWm::t("maillist", "Service Conditions And Privacy Policy", array(), null, $this->language);
        $templateText['Latest News'] = Yii::t("maillist", "Latest News");
        $template = $this->replaceTemplateText($template, $templateText);
        $template = $this->replaceTemplateLoops($template, $templateLoops);
        $this->body = $template;
        $filename = Yii::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . self::$settings['media']['paths']['html']['path'] . DIRECTORY_SEPARATOR . $this->currentTime . ".html";
        $body = str_replace(array("__log__", "__link__"), array("", ""), $this->body);
//        file_put_contents("/var/www/t/t{$this->msgDataset['message_id']}.html", $template);
//        die();
        file_put_contents($filename, $body);
    }

}
