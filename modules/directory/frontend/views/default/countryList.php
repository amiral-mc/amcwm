<?php

$pageContent = "";
//$mediaSettings = $this->module->appModule->mediaSettings;
$options = $this->module->appModule->options;


$pageContent .= '<div class="members_countries">';
if ($countryData) {
    $pageContent .= '<table cellpadding="0" cellspacing="0">';
    $pageContent .= '
        <tr>
            <td class="country_label" rowspan="2" colspan="2"><strong>' . AmcWm::t('msgsbase.core', 'Country') . '</strong></td> 
            <td class="country_label" rowspan="2"><strong>' . AmcWm::t('msgsbase.core', 'Members') . '</strong></td>     
            <td class="members_label" colspan="2" ><strong>' . AmcWm::t('msgsbase.core', 'Joined Members') . '</strong></td>
        </tr>
        <tr>
            <td class="members_label_col" ><strong>' . AmcWm::t('msgsbase.core', 'Active') . '</strong></td>
            <td class="members_label_col" ><strong>' . AmcWm::t('msgsbase.core', 'Associate') . '</strong></td>
        </tr>
    ';
    if (count($countryData)) {
        $c = 1;
        foreach ($countryData as $country) {

            $activeMembersData = new DirectoryListData(0);
            $activeMembersData->setRoute('/directory/default/view');
            $activeMembersData->setCategory(1);
            $activeMembersData->addWhere("t.nationality = '{$country['code']}'");
            $activeMembersData->generate();
            $activeMembers = $activeMembersData->getItems();

            $associateMembersData = new DirectoryListData(0);
            $associateMembersData->setRoute('/directory/default/view');
            $associateMembersData->setCategory(2);
            $associateMembersData->addWhere("t.nationality = '{$country['code']}'");
            $associateMembersData->generate();
            $associateMembers = $associateMembersData->getItems();

            $class = ($c % 2 == 0) ? 'member_row_even' : 'member_row_odd';
            $pageContent .= '
                <tr class="' . $class . '">
                    <td class="country_flg">' . CHtml::image($country['image'], $country['country']) . '</td>
                    <td class="country_name">' . $country['country'] . '</td>
                    <td class="country_name" style="text-align:center">(' . (count($activeMembers) + count($associateMembers)) . ')</td>
                    <td class="member_list">
                        <ul>';
            foreach ($activeMembers as $active) {
                $pageContent .= '<li>' . Html::link($active['company_name'], $active['link']) . '</li>';
            }
            $pageContent .= '
                        </ul>
                    </td>
                    <td class="member_list">
                        <ul>';
            foreach ($associateMembers as $associate) {
                $pageContent .= '<li>' . Html::link($associate['company_name'], $associate['link']) . '</li>';
            }
            $pageContent .= '
                        </ul>
                    </td>
                </tr>';
            $c++;
        }
        $pageContent .= "</table>";
    }
} else if ($directoryData && isset($directoryData['pager']['count']) && $directoryData['pager']['count']) {
    $c = 0;
    $rowSpan = ($options['default']['check']['attachEnable']) ? 4 : 5;
    $pageContent .= "<table cellspacing='1'>";

    foreach ($directoryData['records'] as $data) {
        $bgcolor = (($c % 2 == 0) ? "directory_item_odd" : "directory_item_even");
        if ($options['default']['check']['imageEnable']) {
            if ($data['image'] && $data['settings']['check']['imageEnable']) {
                $drawImage = '<img src="' . $data['image'] . '" border = "0"  alt="' . CHtml::encode($data['company_name']) . '"/>';
            } else {
                $drawImage = '<img src="' . Yii::app()->request->baseUrl . "/images/front/company_dir_pic.png" . '" border = "0"  alt="" />';
            }
            
            $pageContent .= "                    
                    <tr class='{$bgcolor}'>
                        <td class='com_dir_item_logo' rowspan='{$rowSpan}' width='80' valign='top' align='center'>{$drawImage}</td>
                        <td class='directory_item_name'>
                            <b>{$data['company_name']}</b>
                            <div class='dir_desc'>{$data['description']}...</div>
                        </td>
                    </tr>";
        } else {
            $pageContent .= "                    
                    <tr class='{$bgcolor}'>
                        <td class='directory_item_name'>
                            <b>{$data['company_name']}</b>
                            <div class='dir_desc'>{$data['description']}...</div>
                        </td>
                    </tr>";
        }
        $pageContent .= "
                    <tr class='{$bgcolor}'>
                        <td class='com_dir_item_address' >
                            {$data['company_address']}, 
                            {$data['city']} <br />
                            " . ($data['phone'] ? AmcWm::t("msgsbase.core", 'Phone') . ": <span dir='ltr'>{$data['phone']}</span><br />" : "") . "
                            " . ($data['mobile'] ? AmcWm::t("msgsbase.core", 'Mobile') . ": <span dir='ltr'>{$data['mobile']}</span><br />" : "") . "
                            " . ($data['fax'] ? AmcWm::t("msgsbase.core", 'Fax') . ": <span dir='ltr'>{$data['fax']}</span> <br />" : "") . "
                            " . ($data['email'] ? AmcWm::t("msgsbase.core", 'E-mail') . ": {$data['email']}" : "") . "
                        </td>
                    </tr>
                    <tr class='{$bgcolor}'>
                        <td class='com_dir_item_more' >
                            " . ($options['default']['frontend']['showArticleLink'] ? "<div class='dir_more'>" . Html::link(AmcWm::t("msgsbase.core", 'More'), array('/directory/default/view', 'id' => $data['id'])) . "</div>" : "") . "
                        </td>
                    </tr>
                ";
        if ($options['default']['check']['attachEnable']) {
            $drawAttach = "&nbsp;";
            if ($data['attach'] && $data['settings']['check']['attachEnable']) {
                $drawAttach = "<a href='" . $this->createUrl('/site/download', array('f' => $data['attach'])) . "'>" . AmcWm::t("msgsbase.core", "Download Attachment File") . "</a>";
            }
            $pageContent .= "  <tr class='{$bgcolor}'>
                            <td class='com_dir_attach'>
                                {$drawAttach}
                            </td>
                        </tr>";
        }
        $c++;
    }
    $pageContent .= "</table>";

    $pages = new CPagination($directoryData['pager']['count']);
    $pages->setPageSize($directoryData['pager']['pageSize']);

    $pageContent .= '<div class="pager_container" style="margin:0px auto;text-align:center; margin-top:10px;padding-bottom:0px;">';
    $pageContent .= $this->widget('CLinkPager', array('pages' => $pages), true);
    $pageContent .= '</div>';
} else {
    $pageContent .= "<div class='noresult'>";
    $pageContent .= AmcWm::t("msgsbase.core", 'No Result found');
    $pageContent .= "</div>";
}

$pageContent .= '</div>';

//$pageContent = $this->renderPartial("_directory", array('category' => $category, 'dirCategories' => $dirCategories, 'directoryData' => $directoryData), true);
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/directory/default/countryList'), false);
$nationalityCode = AmcWm::app()->request->getParam('code');
if ($nationalityCode) {
    $country = $this->getCountries("", $nationalityCode);
    $breadcrumbs[] = $country;
}

$widgetImage = Data::getInstance()->getPageImage('directory', null, null, Yii::app()->request->baseUrl . '/images/front/company_dir.png');

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", 'Companies Directory'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Companies Directory'),
));
?>