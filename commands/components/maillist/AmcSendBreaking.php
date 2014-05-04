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
class AmcSendBreaking extends AmcMaillist {

    /**
     * @var intger articles limit  
     */
    protected $breakingLimit = 10;

    /**
     * Set the message data
     */
    protected function setMessage() {
        $articlesRoute = 'maillist/default/article';
        if (isset($this->extraOptions['breakingLimit'])) {
            $this->breakingLimit = $this->extraOptions['breakingLimit'];
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
        $sections = array();
        foreach($sectionsDataset as $sectionsRow){
            $sections[] = $sectionsRow['section_id'];
        }        
        $templateVars = array();        
        $templateLoops = array('articlesList'=>array());
        $this->send = true;
        $breaking = new TickerData(array('news'), $this->breakingLimit, 0, $this->language, array('create_date'), $sections);
        $breakingDataset = $breaking->getArticles()->getItems();
        $i = 0;
        $this->send = (boolean) count($breakingDataset);
        foreach ($breakingDataset as $breakingRow) {
            $templateLoops['articlesList'][$i]['articleUrl'] = $this->createUrl($articlesRoute, array('id' => $breakingRow['id'], 'lang' => $this->language, 'm' => $this->msgDataset['message_id'], 'e' => '__user__'));
            $templateLoops['articlesList'][$i]['article'] = $breakingRow['title'];
            $templateLoops['articlesList'][$i]['articleDate'] = date("Y-m-d H:i:s", strtotime($breakingRow['create_date']));
            $i++;
        }
        $template = file_get_contents(AmcWm::app()->basePath . "/../{$this->templateFolder}/template.html");
        $templateVars['templateBaseUrl'] = AmcWm::app()->params['siteUrl'] . "/" . self::$settings['media']['paths']['templates']['path'] . "";
        $templateVars['templateUrl'] = AmcWm::app()->params['siteUrl'] . "/{$this->templateFolder}";
        $templateVars['siteUrl'] = AmcWm::app()->params['siteUrl'];
        $templateVars['lang'] = $this->language;
        $templateVars['link'] = '__link__';
        $templateVars['link'] = '';
        $templateVars['log'] = '__log__';
        $templateVars['unsubscribeUrl'] = $this->createUrl("maillist/default/unsubscribe");
        $templateVars['rssUrl'] = $this->createUrl("rss/default/index");
        $templateVars['contactUs'] = $this->createUrl("site/contact/");
        $templateVars['privacyPolicy'] = $this->createUrl("site/privacy/");
        $templateVars['copyright'] = AmcWm::t("maillist", "_copyright_ {year}", array('{year}' => date('Y')), null, $this->language);
        if ($this->language == 'ar') {
            $templateVars['direction'] = "rtl";
            $templateVars['align'] = "right";
        } else {
            $templateVars['direction'] = "ltr";
            $templateVars['align'] = "left";
        }
        $template = $this->replaceTemplateVars($template, $templateVars);
        $templateText = array();
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
        //file_put_contents("/var/www/t/t{$this->msgDataset['message_id']}.html", $template);
    }

}
