<?php
$eventPages = new CPagination($eventData['pager']['count']);
$eventPages->setPageSize($eventData['pager']['pageSize']);
?>
<div class="events_wraper">
    <?php if (count($eventData['records'])): ?>
        <table width="614px">
            <?php foreach ($eventData['records'] as $eIndex => $event): ?>
                <?php
                $eventClass = (($eIndex + 1) % 2) ? "event_line_odd" : "event_line_even";
                ?>
                <tr>
                    <td class="<?php echo $eventClass ?>">
                        <div><span class="event_icon"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/<?php echo $eventIcon ?>" /></span><span class="event_date">
                                <?php echo Html::link(Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]), array("/events/default/view", 'date' => $date, 'id' => $event["id"])) ?>                                    
                            </span></div>
                        <div class="event_data">
                            <ul class="event_items">                                    
                                <li><b><?php echo Html::link($event["country"] . " : " . $event["location"], array("/events/default/view", 'date' => $date, 'id' => $event["id"])); ?></b></li>
                                <li><b><?php echo Html::link($event["title"], array("/events/default/view", 'date' => $date, 'id' => $event["id"])); ?></b></li>
                                <li class="event_desc"><?php echo Html::utfSubstring($event["event_detail"], 0, 150, true); ?></li>
                            </ul>
                        </div>
                    </td>										
                </tr>
            <?php endforeach; ?>                   
        </table>
        <div class="pager_container">
            <?php $this->widget('CLinkPager', array('pages' => $eventPages)); ?>
        </div>
    <?php else: ?>
        <div class='noresult'><?php echo AmcWm::t("msgsbase.core",  'No Result found'); ?></div>
    <?php endif; ?>
</div>
<?php if (!$past): ?>
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
                        <div><?php echo Html::link($event["title"], array("/events/default/view", 'date' => $date, 'id' => $event["id"]), array("class" => "past_event_title")); ?></div>
                    </td>
                </tr>                    
            <?php endforeach; ?>                   
        </table>
    </div>
    <?php
    $actions = $this->getActionParams();
    $actions['past'] = 1;
    ?>
    <div class="past_event_more"><a href="<?php echo Html::createUrl("/events/default/index", $actions); ?>"><?php echo AmcWm::t("msgsbase.core", "More") ?></a></div>
<?php endif; ?>
<div style="clear: both"></div>