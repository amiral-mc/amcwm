<?php
$baseUrl = Yii::app()->request->baseUrl;
$content = '<p>' . AmcWm::t("app", "RSS Service message") . '</p>'. PHP_EOL;
$content .= '<div class="rss-sections-container">' . PHP_EOL;
$c = 0;
foreach ($sections AS $section){
    
    $encodedUrl = urlencode(Yii::app()->request->getHostInfo(). Html::createUrl(RssSections::$RSS_ROUTE, array('sectionId' => $section['section_id'], 'list'=>0)));
    
    $rowClass = ($c%2==0)?"rss-row-odd":"rss-row-even";
    $content .= "<div class='$rowClass'>";
        $content .= '<ul class="rss-main-channel">';
        $content .= '<li class="rss-link">';
            $content .= Html::link($section['section_name'], array(RssSections::$RSS_ROUTE, 'sectionId' => $section['section_id'], 'list'=>0));
        $content .= '</li>';
        $content .= '<li>';
            $content .= Html::link("<img src='{$baseUrl}/images/front/rss.png' border=0 />", array(RssSections::$RSS_ROUTE, 'sectionId' => $section['section_id'], 'list'=>0));
        $content .= '</li>';
        $content .= '<li>';
            $content .= "<a href='http://fusion.google.com/add?feedurl=" . $encodedUrl . "' ><img src='{$baseUrl}/images/button-google.jpg' border=0 /></a>";
        $content .= '</li>';
        $content .= '<li>';
            $content .= "<a href='http://add.my.yahoo.com/rss?url=" . $encodedUrl . "' ><img src='{$baseUrl}/images/button-yahoo.jpg' border=0 /></a>";
        $content .= '</li>';
        $content .= '<li>';
            $content .= "<a href='http://my.msn.com/addtomymsn.armx?id=rss&ut=" . $encodedUrl . "' ><img src='{$baseUrl}/images/button-msn.jpg' border=0 /></a>";
        $content .= '</li>';
        $content .= '</ul>';
    $content .= '</div>';
    $c++;
}
$content .= '</div>' . PHP_EOL;
$content .= '<p><a href="http://feeds.feedburner.com/anaonline/BWCu" rel="alternate" type="application/rss+xml" target="_blank"><img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png" alt="" style="vertical-align:middle;border:0"/> &nbsp; '.AmcWm::t("amcFront", "Subscribe in a reader").'</a></p>';
$breadcrumbs[] = AmcWm::t("amcFront", "RSS Service");
$this->widget('PageContentWidget', array(
    'id' => 'rss_list',
    'contentData' => $content,
    'title' => AmcWm::t("amcFront", "RSS Service"),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>