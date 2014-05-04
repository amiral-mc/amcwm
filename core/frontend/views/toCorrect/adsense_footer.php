<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$siteLanguage = Yii::app()->user->getCurrentLanguage();
if($siteLanguage == "ar"){
    
    ?>
    <script type="text/javascript"><!--
    google_ad_client = "ca-pub-4497825201096868";
    /* A_ROS_FullBanner_Article */
    google_ad_slot = "8100119792";
    google_ad_width = 468;
    google_ad_height = 60;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    <?php
    
}else{
    
    ?>
    <script type="text/javascript"><!--
    google_ad_client = "ca-pub-4497825201096868";
    /* E_ROS_FullBanner_Article */
    google_ad_slot = "8125204114";
    google_ad_width = 468;
    google_ad_height = 60;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    <?php
    
}


?>
