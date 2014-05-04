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
class AmcSendDefault extends AmcMaillist {

    protected function setMessage() {
        $this->send = true;        
        $template = file_get_contents(AmcWm::app()->basePath . "/../{$this->templateFolder}/template.html");
        $templateVars = array();
        if ($template) {
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
            $templateVars['body'] = $this->msgDataset['body'];
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
            $this->body = $template;
            
//            file_put_contents("/var/www/t/t{$this->msgDataset['message_id']}.html", $template);
        } else {
            $this->body = '__log__';
            $this->body .= $this->msgDataset['body'];
        }        
        $this->body = $template;
    }

}
