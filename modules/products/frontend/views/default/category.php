<?php
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/products/default/category', 'id'=>$id));
//$breadcrumbs[] = AmcWm::t('msgsbase.core', "Products");
$firstProductImage = '';
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . $firstProductImage, "og:image");
?>
