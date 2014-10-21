<?php 
$js_code = "
    $('#catId').change(function(){
        if(this.value){
            document.location.href = '" . Html::createUrl('/glossary/default/index') . (Yii::app()->getUrlManager()->getUrlFormat() == 'path' ? "?" : "&") . "catId='+this.value;
        }else{
            document.location.href = '" . Html::createUrl('/glossary/default/index'). "';
        }
    });
";
Yii::app()->getClientScript()->registerScript('directoryGetCompanies', $js_code, CClientScript::POS_READY);
?>
<div id="glossary_alphabetic" >
    <h3><?php echo AmcWm::t("msgsbase.core",  'Alphabetic View'); ?></h3>									
    <ul class="listTypeAlpha">
        <?php
        if (count($alphabet)) {
            foreach ($alphabet as $k => $v) {
                echo "<li>";
                echo Html::link(ucfirst($v), array('/glossary/default/index', 'q' => $v, 'a' => 1));
                echo "</li>";
            }

            // search for the exp. starts with numbers
            echo "<li>";
            echo Html::link("#", array('/glossary/default/index', 'q' => '#', 'a' => 1));
            echo "</li>";
            echo "<li>";
            echo Html::link(AmcWm::t("msgsbase.core",  'All'), array('/glossary/default/index'));
            echo "</li>";
        }
        ?>
    </ul>									
</div>
<div id="glossary_search">
    <h3><?php echo AmcWm::t("msgsbase.core",  'Glossary Search'); ?></h3>
    
        <?php
            $glossaryForm = $this->beginWidget('CActiveForm', array(
                'id' => 'glossary_search_form',
                'action' => array('/glossary/default/index', 'lang'=>  AmcWm::app()->getLanguage()),
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'method' => 'get',
            ));
        ?>
        <div class="search_box_bg">
            <input type="text" class="search_box_input" name="q" value="<?php echo CHtml::encode(Yii::app()->request->getParam('q')); ?>">
            <input type="image" class="search_btn" value="" src="<?php echo Yii::app()->request->baseUrl; ?>/images/front/searchsubmit.gif">
        </div>
        <div style="clear: both;margin: 0px 8px;">
            <h3 style="padding:4px 0px;"><?php echo AmcWm::t("msgsbase.core", 'Glossary Categories'); ?></h3>        
            <?php echo CHtml::dropDownList('catId', $categoryId, $categoriesList, array('style' => 'width:200px;', 'onchange' => '', 'prompt'=>AmcWm::t("msgsbase.core", 'View all'))); ?>
        </div>
        <?php $this->endWidget(); ?>    
    <!--<div class="glossary_search_ex"><?php //echo AmcWm::t("msgsbase.core",  'Exp.'); ?> : <span>REVOLUTION PER MINUTE</span></div>-->								
</div>

<div id="glossary_results">
    <div class="glossary_results_header">
        <?php echo AmcWm::t("msgsbase.core",  'Found rows {count}', array('{count}' => '<span>' . $glossaryData['pager']['count'] . '</span>')); ?> 
    </div>
    <div class="glossary_items">
        <?php
        if ($glossaryData['pager']['count']) {
            $c = 0;
            echo "<table cellspacing='1'>";
            foreach ($glossaryData['records'] as $data) {
                $bgcolor = (($c % 2 == 0) ? "glossary_item_odd" : "glossary_item_even");
                echo "
                        <tr class='{$bgcolor}'>
                            <td class='glossary_item_label'>" . AmcWm::t("msgsbase.core",  'Expression') . " :</td>
                            <td class='glossary_item_name'>{$data['expression']}</td>
                        </tr>
                        <tr class='{$bgcolor}'>
                            <td class='glossary_item_label'>" . AmcWm::t("msgsbase.core",  'Meaning') . " :</td>
                            <td class='glossary_item_name'>{$data['title']}</td>
                        </tr>
                        <tr class='{$bgcolor}'>
                            <td class='glossary_item_label'>" . AmcWm::t("msgsbase.core",  'Description') . " :</td>
                            <td class='glossary_item_desc'>{$data['description']}</td>
                        </tr>
                        <tr>
                            <td colspan='2' style='border-top:1px solid #b9b9b9; font-size:1px;'></td>
                        </tr>
                    ";
                $c++;
            }
            echo "</table>";
            $pages = new CPagination($glossaryData['pager']['count']);
            $pages->setPageSize($glossaryData['pager']['pageSize']);
            echo '<div class="pager_container" style="margin:0px auto;text-align:center; margin-top:10px;padding-bottom:0px;">';
            $this->widget('CLinkPager', array('pages' => $pages));
            echo '</div>';
        } else {
            echo "<div class='noresult'>";
            echo AmcWm::t("msgsbase.core",  'No Result found');
            echo "</div>";
        }
        ?>
    </div>
</div>