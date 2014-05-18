<?php  echo Yii::t("events", "New agenda item has been inserted");?>
<br />
<?php  echo Yii::t("events", "Agenda item");?>: <?php echo $eventHeader?>
<br />
<?php  echo Yii::t("events", "For adding services please click the following link");?>
<br />
<?php 
$link = Yii::app()->request->getHostInfo() . Html::createUrl('/events/default/services', array('id'=>$id));
echo '<a href="'.$link.'">'.$link.'</a>';
?>

