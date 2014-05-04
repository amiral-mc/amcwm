<div class="events_wraper">                
    <table width="100%">
        <tr>
            <td class="event_line_odd">
                <div class="event_details">
                    <ul class="event_details_items">
                        <li>
                            <div>
                                <span class="event_icon">
                                    <span><?php echo AmcWm::t("msgsbase.core", "Date and time") ?>:</span>
                                </span>
                                <span class="event_date"><?php echo Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]) ?></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="event_icon">
                                    <span><?php echo AmcWm::t("msgsbase.core", "Event location") ?>:</span>
                                </span>
                                <span class="event_date"><?php echo $event["country"] . " : " . $event["location"]; ?></span>
                            </div>
                        </li>                                    
                        <li class="event_details_desc">
                            <div>
                                <span class="event_icon">
                                    <span><?php echo AmcWm::t("msgsbase.core", "Event Details") ?>:</span>
                                </span>
                                <span><?php echo $event["event_detail"]; ?></span>
                            </div>
                        </li>                                                                        
                    </ul>
                </div>
            </td>										
        </tr>
    </table>     
</div>

<div class="past_events_wraper">
    <table width="614px">                    
        <tr>
            <td class="past_events_main_title"><?php echo AmcWm::t("msgsbase.core", 'More events') ?></td>
        </tr>
        <?php foreach ($pastData as $event): ?>
            <tr>
                <td class="past_event_line">
                    <div>
                        <span class="event_icon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/calendar_date.png"/></span>                                                                    
                        <span class="past_event_date"><?php echo Html::link(Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]), array("/events/default/view", 'date' => $date, 'id' => $event["id"])) ?></span>
                    </div>
                    <div><?php echo Html::link($event["title"], array("/events/default/view", 'id' => $event["id"]), array("class" => "past_event_title")); ?></div>
                </td>
            </tr>                    
        <?php endforeach; ?>                   
    </table>
</div>
<?php
$actions = $this->getActionParams();
?>            
<div class="past_event_more">
    <a href="<?php echo Html::createUrl("/events/default/index", $actions); ?>"><?php echo AmcWm::t("msgsbase.core", "More") ?></a>
</div>
<div style="clear: both"></div>