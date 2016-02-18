<?php
$mediaSettings = $this->module->appModule->mediaSettings;
$options = $this->module->appModule->options;
$menu = Yii::app()->request->getParam('menu');
$categorySelect = (int)Yii::app()->request->getParam('c');
$id = (int)Yii::app()->request->getParam('id');
$urlParams = array();
if($menu){
    $urlParams['menu']=$menu;
}
if($id){
    $urlParams['id']=$id;
}
$url = Html::createUrl('/documents/default/index', $urlParams);
$jsCode = "
    function getDoc(){
        $('#directory_search_frm_slct').submit();
    }
";
Yii::app()->getClientScript()->registerScript('docsGetByname', $jsCode, CClientScript::POS_HEAD);
$this->beginClip('documentsList');
?>
<div id="document_alphabetic">    
    <h3><?php echo AmcWm::t('msgsbase.core', 'Categories'); ?></h3>    
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
            $selectParam = array('style' => 'width:280px;', 'onchange' => 'getDoc()', );
            if(count($dirCategories)> 1){
                $selectParam['prompt']= $viewAll;
            }            
            echo CHtml::dropDownList('c', $categorySelect, $dirCategories, $selectParam); 
            ?>
        <?php $this->endWidget(); ?>
    </div>
    <div class="com_dir_search_ex"><?php echo AmcWm::t('msgsbase.core', 'Please select a category for fast search'); ?><span></span></div>
</div>


<div id="document_search">
    <h3><?php echo AmcWm::t('msgsbase.core', 'Search'); ?></h3>
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
        <input type="text" class="search_box_input" name="q" value="<?php echo CHtml::encode(Yii::app()->request->getParam('q')); ?>">
        <input type="image" class="search_btn" value="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/front/searchsubmit.gif">
        <?php $this->endWidget(); ?>
    </div>
</div>

<div id="document_results">
    <div class="document_results_header">
        <?php echo AmcWm::t('msgsbase.core', 'Found rows {count}', array('{count}' => '<span>' . $directoryData['pager']['count'] . '</span>')); ?> 
    </div>
    <div class="document_items">
        <?php
        if ($directoryData['pager']['count']) {
            $c = 0;
            echo "<table cellspacing='1'><tr class='document_item_even'>";
            foreach ($directoryData['records'] as $data) {
//                $modd = (($c % 1 == 1) ? true : false);
//                $bgcolor = (($modd) ? "document_item_odd" : "document_item_even");
                $langSettings = AmcWm::app()->appModule->getSettings('languages');
                $fileLang = $langSettings[$data['file_lang']];
                $drawDocLink = "&nbsp;";
                if($data['file_ext']){
                    $drawDocLink = "&nbsp; (<a href='".Html::createUrl('/site/download', array('f'=>"{$mediaSettings['paths']['files']['path']}/{$data['id']}.{$data['file_ext']}"))."' style='color:#16497E; text-decoraion:none; font-weight:bold'>".AmcWm::t('msgsbase.core', 'Download')."</a>)";
                }
                echo "<td width='50%' valign='top'>";
                echo "<table cellspacing='1'>";
                echo "<tr>
                            <td class='com_doc_item_logo' rowspan='2' width='20'>
                                " . (
                                        ($data['file_ext']) ? "
                                            <img src='" . Yii::app()->request->baseUrl . "/images/docs/{$data['file_ext']}.png' width='20'/>
                                            " : "
                                            <img src='" . Yii::app()->request->baseUrl . "/images/front/document_file.png' width='20'/>
                                            "
                                    ) . "
                            </td>
                            <td class='document_item_name'>
                                {$data['title']}
                            </td>
                        </tr>
                        <tr>
                            <td class='com_docs_item_desc' style='font-size:11px;'>
                                ".AmcWm::t('msgsbase.core', 'File Language: {lang}', array('{lang}'=>$fileLang))."
                                {$drawDocLink} <br />
                                {$data['description']}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                            </td>
                        </tr>
                    ";
                echo "</table>";
                echo "</td>";
                echo ($c%2==1)?"</tr><tr class='document_item_even'>":"";
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
            echo AmcWm::t('msgsbase.core', 'No Result found');
            echo "</div>";
        }
        ?>
    </div>
</div>
<?php
$this->endClip('documentsList');

$pageContentTitle = AmcWm::t('msgsbase.core', 'Documents');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array($options['default']['text']['homeRoute']), false);
$title = AmcWm::t('msgsbase.core', 'Documents');
$catImage = null;

if(isset($dirCategoriesData['category_name'])){
//    $breadcrumbs[] = $dirCategoriesData['category_name'];
    $pageContentTitle = $dirCategoriesData['category_name'];
    $title = $dirCategoriesData['category_name'];
    if($dirCategoriesData['image_ext']){
        $catImage = Yii::app()->request->baseUrl .'/'. $mediaSettings['categories']['path'] . '/' . $dirCategoriesData['category_id'] . "." . $dirCategoriesData['image_ext'];
    }
}

$widgetImage = Data::getInstance()->getPageImage('documents', null, $catImage);


$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $this->clips['documentsList'],
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentTitle,
));

?>