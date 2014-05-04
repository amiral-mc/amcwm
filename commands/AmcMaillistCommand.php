<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */

AmcWm::import("amcwm.commands.components.maillist.*");
/**
 * @example /var/www/mts/protected/amcc maillist --cmd=all --options=articlesLimit:10
 */
class AmcMaillistCommand extends CConsoleCommand {

    public function actionIndex($limit = 50, $cmd = null, $lang = null,  array $options = null) {        
        $limit = (int) trim($limit);
        $currentDate = date("Y-m-d H:i:s");
        $commandChannelWhere = 
        $commandChannelWhere = 'and c.channel_command is null';        
        if ($cmd) {
            $commandChannelWhere = ($cmd == 'all') ? "" : sprintf('and c.channel_command = %s', Yii::app()->db->quoteValue($cmd), Yii::app()->db->quoteValue($lang));
        }         
        $channelJoin = "";
        $channelWhere = "";
        if($lang){
            $channelJoin = sprintf("and c.content_lang =%s", Yii::app()->db->quoteValue($lang));
            $channelWhere = "and (c.content_lang is not null or m.channel_id is null)";
        }
        $cmdQuery = sprintf("select 
            m.id message_id, 
            m.channel_id,             
            m.subject, 
            m.body, 
            m.cron_time,
            m.cron_condition,
            m.cron_step,
            m.cron_start,
            m.cron_end,
            t.template, 
            c.content_lang,
            c.channel_command,
            c.channel,
            c.auto_generate
            from maillist_message m             
            left join maillist_channels_templates t on m.template_id = t.template_id
            left join maillist_channels c on c.id = m.channel_id {$channelJoin} and c.published = %d
            where m.published = %d 
            and m.cron_start <= '{$currentDate}'and (m.cron_end  >= '{$currentDate}' or m.cron_end is null)
            {$channelWhere}
            
            $commandChannelWhere
            ", ActiveRecord::PUBLISHED, ActiveRecord::PUBLISHED
        );
//        die("\n\n" . $cmdQuery . "\n\n");    
//        echo "\n\n\n";
        $messages = Yii::app()->db->createCommand($cmdQuery)->queryAll();
        //echo "\nPeriod\t\tStart\t\t\t\tnext\t\t\t\tcurrent\n";
        foreach ($messages as $message) {            
            if($message['channel_command']){
                $className = "AmcSend" . ucfirst($message['channel_command']);
            }
            else{
                $className = "AmcSendDefault";
            }
            $sendClass = new $className($this, $message, $lang ,$limit, $options);
            $sendClass->run();
            //echo("\n" . $className . "\n");    
        }
        $msg = PHP_EOL . 'Done..' . PHP_EOL;
        echo $msg;
        exit;
    }

    protected function createPostUrl($route, $params = array()) {
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }
        return Html::createLinkRoute($url, $route, $params);
    }

    public function init() {
        set_time_limit(0);
        ignore_user_abort(true);
    }

}

