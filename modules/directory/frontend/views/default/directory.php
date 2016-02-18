<?php
$options = $this->module->appModule->options;
$menu = Yii::app()->request->getParam('menu');
$categorySelect = (int)Yii::app()->request->getParam('c');
$id = (int)Yii::app()->request->getParam('id');
$urlParams = array();
if ($menu) {
    $urlParams['menu'] = $menu;
}
if ($id) {
    $urlParams['id'] = $id;
}
$url = Html::createUrl('/directory/default/index', $urlParams);
$jsCode = "
    function getDoc(){
        $('#directory_search_frm_slct').submit();
    }
";
Yii::app()->getClientScript()->registerScript('directoryGetCompanies', $jsCode, CClientScript::POS_HEAD);
$this->beginClip('directoryList');
?>

<?php if ($options['default']['frontend']['categoriesFilterEnable']): ?>
    <div id="directory_alphabetic">
        <h3><?php echo AmcWm::t("msgsbase.core", 'Companies Categories'); ?></h3>
        <div class="com_dir_cat_box">
            <?php
            $this->beginWidget('CActiveForm', array(
                'id' => 'directory_search_frm_slct',
                'action' => $url,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'method' => 'get',
            ));
            ?>

            <?php
            $selectParam = array('style' => 'width:280px;', 'onchange' => 'getDoc()',);
            if (count($dirCategories) > 1) {
                $selectParam['prompt'] = $viewAll;
            }
            echo CHtml::dropDownList('c', $categorySelect, $dirCategories, $selectParam);
            ?>
            <?php $this->endWidget(); ?>
        </div>
        <div class="com_dir_search_ex"><?php echo AmcWm::t("msgsbase.core", 'Please select a category for fast search'); ?><span></span></div>
    </div>
<?php endif; ?>

<?php if ($options['default']['frontend']['searchEnable']): ?>
    <div id="directory_search">
        <h3><?php echo AmcWm::t("msgsbase.core", 'Directory Search'); ?></h3>
        <div class="search_box_bg">
            <?php
            $this->beginWidget('CActiveForm', array(
                'id' => 'directory_search_form',
                'action' => $url,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'method' => 'get',
            ));
            ?>
            <?php if ($categorySelect): ?>
                <input type="hidden" name="c" value="<?php echo $categorySelect; ?>">
            <?php endif; ?>
            <input type="text" class="search_box_input" name="q" value="<?php echo CHtml::encode(Yii::app()->request->getParam('q')); ?>">
            <input type="image" class="search_btn" value="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/front/searchsubmit.gif">
            <?php $this->endWidget(); ?>
        </div>
        <!--<div class="directory_search_ex"><?php echo AmcWm::t("msgsbase.core", 'Exp.'); ?> : <span>Royal logistics int</span></div>-->	
        <ul class="listTypeAlpha">
            <?php
            if (count($alphabet)) {
                foreach ($alphabet as $k => $v) {
                    echo "<li>";
                    echo Html::link(ucfirst($v), array('/directory/default/index', 'q' => $v, 'a' => 1));
                    echo "</li>";
                }
            }
            ?>
        </ul>		
    </div>
<?php endif; ?>


<div id="directory_results">

    <?php if ($options['default']['frontend']['searchEnable']): ?>
        <div class="directory_results_header">
            <?php echo AmcWm::t("msgsbase.core", 'Found rows {count}', array('{count}' => '<span>' . $directoryData['pager']['count'] . '</span>')); ?> 
        </div>
    <?php endif; ?>

    <div class="directory_items">
        <?php
        if ($directoryData['pager']['count']) {
            $c = 0;
            $rowSpan = ($options['default']['check']['attachEnable']) ? 4 : 5;
            echo "<table cellspacing='1'>";

            foreach ($directoryData['records'] as $data) {
                $bgcolor = (($c % 2 == 0) ? "directory_item_odd" : "directory_item_even");
                if ($options['default']['check']['imageEnable']) {
                    if ($data['image'] && $data['settings']['check']['imageEnable']) {
                        $drawImage = '<img src="' . $data['image'] . '" border = "0"  alt="' . CHtml::encode($data['company_name']) . '"/>';
                    } else {
                        $drawImage = '<img src="' . Yii::app()->request->baseUrl . "/images/front/company_dir_pic.png" . '" border = "0"  alt="" />';
                    }

                    echo "                    
                        <tr class='{$bgcolor}'>
                            <td class='com_dir_item_logo' rowspan='{$rowSpan}' width='80' valign='top'>{$drawImage}</td>
                            <td class='directory_item_name'><b>{$data['company_name']}</b>
                                " . ($data['activity'] ? "<div class='directory-activity'>" . AmcWm::t("msgsbase.core", 'Company Activity') . ": {$data['activity']}</div>" : "") . "
                            </td>
                        </tr>";
                } else {
                    echo "                    
                        <tr class='{$bgcolor}'>
                            <td class='directory_item_name'><b>{$data['company_name']}</b>
                                " . ($data['activity'] ? "<div class='directory-activity'>" . AmcWm::t("msgsbase.core", 'Company Activity') . ": {$data['activity']}</div>" : "") . "
                            </td>
                        </tr>";
                }
                echo "        
                        <tr class='{$bgcolor}'>
                            <td class='com_dir_item_address'>
                                {$data['company_address']}, 
                                {$data['city']}
                            </td>
                        </tr>
                        <tr class='{$bgcolor}'>
                            <td class='com_dir_item_address' >
                                " . ($data['phone'] ? AmcWm::t("msgsbase.core", 'Phone') . ": <span dir='ltr'>{$data['phone']}</span><br />" : "") . "
                                " . ($data['mobile'] ? AmcWm::t("msgsbase.core", 'Mobile') . ": <span dir='ltr'>{$data['mobile']}</span><br />" : "") . "
                                " . ($data['fax'] ? AmcWm::t("msgsbase.core", 'Fax') . ": <span dir='ltr'>{$data['fax']}</span> <br />" : "") . "
                                " . ($data['email'] ? AmcWm::t("msgsbase.core", 'E-mail') . ": {$data['email']}" : "") . "
                                " . ($data['url'] ? AmcWm::t("msgsbase.core", 'Website') . ": {$data['url']}" : "") . "
                                " . ($options['default']['frontend']['showArticleLink'] ? "<div class='dir_more'>" . Html::link(AmcWm::t("msgsbase.core", 'More'), array('/directory/default/view', 'id' => $data['id'])) . "</div>" : "") . "
                            </td>
                        </tr>                       
                    ";
//                                 <tr class='{$bgcolor}'>
//                            <td class='com_dir_item_desc'></td>
//                        </tr>
                if ($options['default']['check']['attachEnable']) {
                    $drawAttach = "&nbsp;";
                    if ($data['attach'] && $data['settings']['check']['attachEnable']) {
                        $drawAttach = "<a href='" . Html::createUrl('/site/download', array('f' => $data['attach'])) . "'>" . AmcWm::t("msgsbase.core", "Download Attachment File") . "</a>";
                    }
                    echo "  <tr class='{$bgcolor}'>
                            <td class='com_dir_attach'>
                                {$drawAttach}
                            </td>
                        </tr>";
                }
                $c++;
            }
            echo "</table>";
            $pages = new CPagination($directoryData['pager']['count']);
            $pages->setPageSize($directoryData['pager']['pageSize']);
            echo '<div class="pager_container" style="margin:0px auto;text-align:center; margin-top:10px;padding-bottom:0px;">';
            $this->widget('CLinkPager', array('pages' => $pages));
            echo '</div>';
        } else {
            echo "<div class='noresult'>";
            echo AmcWm::t("msgsbase.core", 'No Result found');
            echo "</div>";
        }
        ?>
    </div>
</div>
<?php
$this->endClip('directoryList');

$breadcrumbs = Data::getInstance()->getBeadcrumbs(array($options['default']['text']['homeDirectoryRoute']), false);
$widgetImage = Data::getInstance()->getPageImage('directory', null, null, Yii::app()->request->baseUrl . '/images/front/company_dir.png');

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $this->clips['directoryList'],
    'title' => AmcWm::t("msgsbase.core", 'Companies Directory'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Companies Directory'),
));
?>