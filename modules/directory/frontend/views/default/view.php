<?php

$mediaSettings = AmcWm::app()->appModule->mediaSettings;
$options = $this->module->appModule->options;
$pageContent = $dirContent = $dirContact = '';
$relatedArticles = array();
$pageContent .= '<div>';
if ($directoryData) {
    $pageContent .= "<table border='0' cellspacing='1' style='border-collapse: collapse; width:100%;   '>";
    if ($options['default']['check']['imageEnable']) {
        if ($directoryData['image']) {
            $drawImage = '<img src="' . $directoryData['image'] . '" border = "0"  alt="' . CHtml::encode($directoryData['company_name']) . '"/>';
        } else {
            $drawImage = '<img src="' . Yii::app()->request->baseUrl . "/images/front/company_dir_pic.png" . '" border = "0"  alt="" />';
        }

        $dirContact .= "                    
                <tr class='dirCompany'>
                    <td class='com_dir_item_logo' rowspan='8' width='80' valign='top'>{$drawImage}</td>
                    <td class='glossary_item_name'>{$directoryData['company_name']}</td>
                </tr>";
    }

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address'>";
    $dirContact .= $this->drawExtended($directoryData, 'company_address', false, "", "&nbsp;{$directoryData['city']}");
    $dirContact .= "</td>";
    $dirContact .= "</tr>";

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address' >";
    $dirContact .= $this->drawExtended($directoryData, 'phone', true, AmcWm::t("msgsbase.core", 'Phone'));
    $dirContact .= "</td>";
    $dirContact .= "</tr>";

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address' >";
    $dirContact .= $this->drawExtended($directoryData, 'mobile', true, AmcWm::t("msgsbase.core", 'Mobile'));
    $dirContact .= "</td>";
    $dirContact .= "</tr>";

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address' >";
    $dirContact .= $this->drawExtended($directoryData, 'fax', true, AmcWm::t("msgsbase.core", 'Fax'));
    $dirContact .= "</td>";
    $dirContact .= "</tr>";

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address' >";
    $dirContact .= $this->drawExtended($directoryData, 'email', true, AmcWm::t("msgsbase.core", 'Email'));
    $dirContact .= "</td>";
    $dirContact .= "</tr>";

    $dirContact .= "<tr class='dirCompany'>";
    $dirContact .= "<td class='com_dir_item_address' >";
    $dirContact .= $this->drawExtended($directoryData, 'url', true, AmcWm::t("msgsbase.core", 'Website'));
    $dirContact .= "</td>";
    $dirContact .= "</tr>";
    $dirContact .= "";
    $dirContact .= "";

    if ($options['default']['check']['attachEnable']) {
        $drawAttach = "&nbsp;";
        if ($directoryData['attach'] && $directoryData['settings']['check']['attachEnable']) {
            $drawAttach = "<a href='" . $this->createUrl('/site/download', array('f' => $directoryData['attach'])) . "'>" . AmcWm::t("msgsbase.core", "Download Attachment File") . "</a>";
        }
        $dirContact .= "  <tr class='dirCompany'>
                    <td class='com_dir_attach'>
                        {$drawAttach}
                    </td>
                </tr>";
    }

    $dirMaps = '';
    if ($options['default']['check']['mapEnable']) {
        $enabled = false;
        if ($directoryData['maps']) {
            $mapsData = CJSON::decode($directoryData['maps']);
            $zoom = (isset($mapsData['location']['zoom'])) ? $mapsData['location']['zoom'] : null;
            $lat = (isset($mapsData['location']['lat'])) ? $mapsData['location']['lat'] : null;
            $lng = (isset($mapsData['location']['lng'])) ? $mapsData['location']['lng'] : null;
            $enabled = (isset($mapsData['location']['enabled'])) ? $mapsData['location']['enabled'] : $enabled;
        }

        if ($enabled && $lat) {
            $js = " var marker = null;
                function initialize() {
                    var mapOptions = {
                      zoom: {$zoom},
                      center: new google.maps.LatLng({$lat}, {$lng}),
                      mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    var map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);

                    var selectedLocation = new google.maps.LatLng({$lat}, {$lng});
                    marker = new google.maps.Marker({
                                position: selectedLocation,
                                map: map
                          });
                    map.panTo(selectedLocation);
                }
          google.maps.event.addDomListener(window, 'load', initialize);";

            Yii::app()->getClientScript()
                    ->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', CClientScript::POS_HEAD)
                    ->registerScript('__GMAP', $js, CClientScript::POS_READY);
        }

        if ($directoryData['company_id'] && isset($mapsData['image'])) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['maps']['path'] . "/" . $directoryData['company_id'] . "." . $mapsData['image']))) {
                $dirMaps .= '<div align="center">' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['maps']['path'] . "/" . $directoryData['company_id'] . "." . $mapsData['image'] . "?" . time(), "", array("class" => "image", "style" => "max-width:500px")) . '</div>';
            }
        }

        $dirMaps .= '<br />';
        $dirMaps .= '<div id="map-canvas" style="width: 100%; height: 300px;"></div>';
    }

    $view = AmcWm::app()->request->getParam('v');
    switch ($view) {
        case 'desc':
        default :
            $pageContent .= ($directoryData['activity'] ? "<div class='directory-activity'>" . AmcWm::t("msgsbase.core", 'Company Activity') . ": {$directoryData['activity']}</div>" : "");
            if ($directoryData['description']) {
                $pageContent .= $directoryData['description'];
            } else {
                $pageContent .= AmcWm::t('msgsbase.core', 'No data Available');
            }
            break;
        case 'contact':
            $pageContent .= $dirContact;
            break;
        case 'maps':
            $pageContent .= $dirMaps;
            break;
    }
    $pageContent .= "</table>";
    $pageContent .= '</div>';

    $view = AmcWm::app()->request->getParam('v');
    $relatedArticles = array();
    $relatedArticles[] = array(
        'title' => AmcWm::t("msgsbase.core", 'Description'),
        'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v' => 'desc'),
        'active' => ($view == 'desc') ? true : ($view ? false : true),
    );

    if (count($articles)) {
        foreach ($articles as $article) {
            $relatedArticles[] = array(
                'title' => $article['title'],
                'url' => $article['link'],
                'active' => 0,
            );
        }
    }
    if ($options['default']['check']['mapEnable']) {
        $relatedArticles[] = array(
            'title' => AmcWm::t("msgsbase.core", 'Maps'),
            'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v' => 'maps'),
            'active' => ($view == 'maps'),
        );
    }
    $relatedArticles[] = array(
        'title' => AmcWm::t("msgsbase.core", 'Contact'),
        'url' => array('/directory/default/view', 'id' => $directoryData['company_id'], 'v' => 'contact'),
        'active' => ($view == 'contact'),
    );
} else {
    $pageContent .= "<div class='noresult'>";
    $pageContent .= AmcWm::t("msgsbase.core", 'No Result found');
    $pageContent .= "</div>";
}
$country = $this->getCountries("", $directoryData['nationality']);
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array($options['default']['text']['homeDirectoryRoute']), false);
$breadcrumbs[] = $country;

$widgetImage = Data::getInstance()->getPageImage('directory', null, null, Yii::app()->request->baseUrl . '/images/front/company_dir.png');

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'title' => $directoryData['company_name'],
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $directoryData['company_name'],
    'image' => $widgetImage,
    'subItems' => $relatedArticles,
));
?>