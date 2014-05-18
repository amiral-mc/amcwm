<div dir="<?php echo Yii::app()->getLocale()->getOrientation();?>">                       
    <div>                
        <div><b><?php echo Yii::t('agenda', 'Report Request') ?></b></div>
        <table width="614px">
            <tr>
                <td>											
                    <div>
                        <b><?php echo Yii::t('agenda', 'Agenda'); ?> - <?php echo $event["event_header"] ?></b>
                    </div>									
                    <div>
                        <b><?php echo AmcWm::t("msgsbase.core", "Date and time") ?>:</b><?php echo Yii::app()->dateFormatter->format('EEEE dd MMMM yyyy', $event["event_date"]) . '&nbsp;&nbsp;&nbsp' . Yii::app()->dateFormatter->format('h:m a', $event["event_date"]) ?>
                    </div>
                    <div>
                        <b><?php echo AmcWm::t("msgsbase.core", "Event location") ?>:</b><?php echo $event["country"] . " : " . $event["location"]; ?>
                    </div>
                    <div>
                        <?php echo AmcWm::t("msgsbase.core", "Event Details") ?>:<?php echo $event["event_detail"]; ?>
                    </div>
                </td>										
            </tr>
            <tr>
                <td>
                    <div>                        
                        <table cellspacing="2" cellpadding="2">
                            <tr>
                                <td><?php echo AmcWm::t("msgsbase.core", 'Company / Organization') ?>: <?php echo $order->company; ?></td>
                            </tr>
                            <tr>
                                <td> <?php echo AmcWm::t("msgsbase.core", 'Contact Person') ?>: <?php echo $order->contact_person; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo AmcWm::t("msgsbase.core", 'Contact Person Position') ?>: <?php echo $order->contact_person_position; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo AmcWm::t("msgsbase.core", 'email') ?>: <?php echo $order->email; ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo Yii::t('agenda', 'Services'); ?></b></td>
                            </tr>
                            <tr>
                                <td>
                                    <div>                                   
                                        <table align="left" cellspacing="2" cellpadding="2" style="border-collapse: collapse;" border="0">
                                            <?php
                                            //print_r($order->servicesTypes);
                                            ?>
                                            <?php foreach ($_POST['services'] as $serviceTypeId => $serviceIds): ?>
                                                <tr>
                                                    <td colspan="4"><b><?php echo AmcWm::t("msgsbase.core", $order->servicesTypes[$serviceTypeId]['type']); ?></b></td>
                                                </tr>
                                                <?php foreach ($serviceIds as $serviceId): ?>
                                                    <tr>                                                    
                                                        <td><?php echo AmcWm::t("msgsbase.core", $order->servicesTypes[$serviceTypeId]['services'][$serviceId]['service']); ?></td>
                                                        <?php if ($order->servicesTypes[$serviceTypeId]['services'][$serviceId]['extra_units_size']): ?>
                                                            <td>
                                                                <?php echo Yii::t('agenda', 'Add {extra_units} {unit}.', array('{extra_units}' => $order->servicesTypes[$serviceTypeId]['services'][$serviceId]['extra_units_size'], '{unit}' => Yii::t('agenda', $order->servicesTypes[$serviceTypeId]['services'][$serviceId]['unit']))) ?> :                                                         
                                                                <?php echo Yii::t('agenda', 'Number of Unites'); ?>
                                                            </td>     
                                                            <td>
                                                                <?php echo $_POST['extraUnits'][$serviceTypeId][$serviceId] ?>
                                                            </td>
                                                        <?php else: ?>
                                                            <td colspan="2">&nbsp;</td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                            
                                                <tr>
                                                    <td colspan="4">                                                        
                                                        <b>
                                                        <?php 
                                                        if ($_POST['servicesTypeRate'][$serviceTypeId] == "EXE-SPACE"){
                                                            echo Yii::t('agenda', 'Rate card excluding space');
                                                        }
                                                        else if($_POST['servicesTypeRate'][$serviceTypeId] == "INC-SPACE"){
                                                            echo Yii::t('agenda', 'Rate card including space');
                                                        }
                                                        ?>
                                                        </b>
                                                        <hr />
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>



                                        </table>    
                                    </div>
                                </td>
                            </tr>
                        </table>		
                    </div>
                </td>

            </tr>
        </table>     
    </div>            
</div>  	