<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $modelOptions = $this->module->appModule->options;
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo Chtml::hiddenField('pid', $this->getParentId()); ?>
    <?php echo Chtml::hiddenField('mid', $this->getMenuId()); ?>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Menu Item data"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
        </div> 
        <div class="row publish">
            <?php echo $form->checkbox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
            <?php echo $form->error($model, 'published'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'label'); ?>
            <?php echo $form->textField($contentModel, 'label', array('size' => 100, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'label'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'parent_item'); ?>
            <?php AmcWm::pt('msgsbase.core', 'insert in one of this menus') ?>:<br />
            <?php echo $form->dropDownList($model, 'parent_item', $model->getMenuItemsList($this->getMenuData()->levels, AmcWm::t('zii', 'Not set'))); ?>
            <?php echo $form->error($model, 'parent_item'); ?>
        </div>
    </fieldset>


    <script type="text/javascript">
        
        function showMyParams(componentId) {
            $(".componentsParams").each(function (element){
                $(this).hide();
            });
            $('#component_' + componentId).show();
            $('.extraOptions').show();
            setCookie('componentId', componentId, 1);
        }
        
        jQuery(function($) {
            var componentId = getCookie("componentId");
            if (componentId!=null && componentId!=""){
                showMyParams(componentId);
            }
            $(window).unload( function () { setCookie('componentId', '', -10); });
        });
        
        function unChecktheOthers(componentId){
            $(".menuClassCks").change(function() {
                $(".menuClassCks").each(function(){
                    //                    $('#rel_' + this.id).hide();
                });
                //                $('.extraOptions').hide();
                if( this.checked ) {
                    var checkname = $(this).attr("name");
                    $("input:checkbox[name=\"" + checkname + "\"]").removeAttr("checked");
                    this.checked = true;
                    //                    $('#rel_' + this.id).show();
                }else{
                    //                    $('.extraOptions').show();
                }
            });
        };
        
        function getCookie(c_name){
            var i,x,y,ARRcookies=document.cookie.split(";");
            for (i=0;i<ARRcookies.length;i++){
                x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
                y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
                x=x.replace(/^\s+|\s+$/g,"");
                if (x==c_name){
                    return unescape(y);
                }
            }
        }
        
        function setCookie(c_name,value,exdays){
            var exdate=new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
            document.cookie=c_name + "=" + c_value;
        }
        
        function setAttr(attr, attrId, element){
            if(attr == ""){
                alert("<?php echo AmcWm::t("msgsbase.core", "No item has been selected"); ?>");
                return false;
            }
            $(".popUpPreview").each(function(){
                $(this).html('');
            });
            
            $(".popUpHidden").each(function(){
                $(this).val('');
            });
            
            $('#' + element).val(attrId);
            $('#my_' + element).html(" (<b>"+attr+"</b>)");
        }

    </script>
    <div style="min-height: 300px;">
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Menu Item Params"); ?>:</legend>
            <?php
            $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));

            $options = array('' => AmcWm::t("zii", "Not set"));
            $options["url"] = AmcWm::t("msgsbase.core", "Link");

            if ($model->component_id == null) {
                $model->component_id = $model->getComponentIdFroumRoute();
            }

            $output = "";
            $menuItemParams = array();
            if (!$model->isNewRecord) {
                foreach ($model->menuItemsParams as $k => $v) {
                    if ($model->component_id == null && $v['component_id']) {
                        $model->component_id = $v['component_id'];
                    }
                    $menuItemParams[$model->component_id][$v['param_id']] = $v['value'];
                }
            }
            // this sets teh default values on post
            if (isset($model->paramsMenuItemsParams[$model->component_id])) {
                $paramData = $model->paramsMenuItemsParams[$model->component_id];
                foreach ($paramData as $postK => $postParam) {
                    if ($postParam) {
                        if ($postK == "'MENUCLASS'") {
                            $menuItemParams[$model->component_id][$postParam] = '';
                        } else {
                            $menuItemParams[$model->component_id][$postK] = $postParam;
                        }
                    }
                }
            }

            $output .= CHtml::openTag('div', array('id' => 'component_url', 'style' => "display:" . ($model->component_id == 'url' ? 'block;' : 'none;'), 'class' => 'componentsParams row'));
            $output .= "<div class='row'>";
            $output .= $form->labelEx($model, 'link');
            $output .= $form->textField($model, 'link', array('size' => 150, 'maxlength' => 100));
            $output .= $form->error($model, 'link');
            $output .= "<br />" . AmcWm::t("msgsbase.core", "Link description");
            $output .= '</div>';
            $output .= CHtml::closeTag('div');
            /**
             * getComponents, gets all the website component
             */
            $components = MenuItems::getComponents();
            foreach ($components as $component) {
                $options[$component['component_id']] = $component['component_name'];
                $componentSelected = ($component['component_id'] == $model->component_id) ? true : false;

                $rHtmlOptions = array();
                $rHtmlOptions['id'] = 'component_' . $component['component_id'];
                $rHtmlOptions['style'] = $componentSelected ? 'display:block;' : 'display:none;';
                $rHtmlOptions['class'] = 'componentsParams row';

                if ($componentSelected)
                    Yii::app()->getClientScript()->registerCss('componentSelected', '#component_0{display:none}');

                $output .= CHtml::openTag('div', $rHtmlOptions);

                $params = MenuItems::getParamsList($component['component_id']);
                foreach ($params as $param) {
                    $childrenText = "";
                    $paramField = "MenuItemsParams[{$component['component_id']}][{$param['param_id']}]";
                    $paramId = 'param_' . $component['component_id'] . '_' . $param['param_id'];

                    $cHtmlOptions = array();
                    $cHtmlOptions['value'] = $param['param_id'];
                    $cHtmlOptions['id'] = $paramId;
                    $cHtmlOptions['class'] = 'itemsChks';

                    $defaultValue = '';
                    $selected = false;
                    if (isset($menuItemParams[$component['component_id']][$param['param_id']])) {
                        $defaultValue = $menuItemParams[$component['component_id']][$param['param_id']];
                        $selected = true;
                    }

                    if ($param['param_type'] == 'ROUTE') {
                        $childrenText .= '<div class="extraOptions" style="margin:2px 0px;">';
                        if ($param['param'] == 'module') {
                            $childrenText .= CHtml::hiddenField($paramField, $param['module_id']);
                        } else {
                            $select2Defaults = array();
                            $text = null;
                            if ($defaultValue) {
                                $text = ParamsTaskManager::getTitle($param, $defaultValue);
                                $select2Defaults = array('id' => $defaultValue, 'text' => $text);
                            }
                            
                            $childrenText .= $this->widget('amcwm.core.widgets.attachedItem.AttachedItemWidget', array(
                                'param' => $param,
                                'defaultValueData' => $select2Defaults
                            ), true);
                            $childrenText .= "<div style='padding-bottom:5px;'>" . $param['description'] . "</div>";
                        }
                        $childrenText .= '</div>';
                    } else if ($param['param_type'] == 'CODE') {

                        $childrenText .= '<div class="extraOptions" style="margin:2px 0px">';

                        if ($param['param'] == 'view') {
                            $viewLayOut = AmcWm::app()->appModule->options['params']['views'];
                            $childrenText .= Chtml::label($param['label'], $paramId);
                            $childrenText .= "<div>" . $param['description'] . "</div>";
                            $childrenText .= "<table><tr>";
                            foreach ($viewLayOut as $klayout => $vlayout) {
                                $layoutSelected = ($model->isNewRecord) ? ($vlayout === 'default' ? true : false) : ($defaultValue === $vlayout ? true : false);
                                $childrenText .= "<td align='center'><label for='view_" . $klayout . "'><img src='" . $baseScript . "/images/" . $vlayout . ".png'/> <br /> $vlayout <br />" . CHtml::radioButton($paramField, $layoutSelected, array('value' => $vlayout, 'id' => "view_$klayout", 'class' => 'itemsChks')) . "</td>";
                            }
                            $childrenText .= "</tr></table>";
                        } else if ($param['param'] == 'task') {
                            $menuOptionsTasks = AmcWm::app()->appModule->options['params']['tasks'];
                            $menuParamsTaskOptions = array();
                            foreach ($menuOptionsTasks as $menuTaskParamOption) {
                                $menuParamsTaskOptions[$menuTaskParamOption] = AmcWm::t("msgsbase.core", "task_{$menuTaskParamOption}_option");
                            }

                            $childrenText .= Chtml::label($param['label'], $paramId);
                            $childrenText .= "<div>" . $param['description'] . "</div>";
                            $childrenText .= CHtml::dropDownList($paramField, $defaultValue, $menuParamsTaskOptions, array('id' => $paramId));
                        } else {
                            $childrenText .= Chtml::checkBox($paramField, $selected, $cHtmlOptions);
                            $childrenText .= Chtml::label($param['label'], $paramId, array("class" => 'normal_label'));
                            $childrenText .= "<div>" . $param['description'] . "</div>";
                        }
                        $childrenText .= '</div>';
                    } else if ($param['param_type'] == "MENU_CLASS") {

                        $select2Defaults = array();
                        $text = null;
                        if ($defaultValue) {
                            $defaultValueData = CJSON::decode($defaultValue);
                            if($defaultValueData['id'])
                                $text = ParamsTaskManager::getTitle($param, $defaultValueData['id']);
                            $select2Defaults = array('id' => $defaultValueData['id'], 'text' => $text);
                        }
                        
//                        if ($selected)
//                            Yii::app()->getClientScript()->registerCss('chkIfCkecked', '.extraOptions{display:none}');

//                        $cHtmlOptions['class'] = 'menuClassCks';
//                        $cHtmlOptions['onclick'] = "unChecktheOthers({$component['component_id']})";

                        $childrenText = "<div id='rel_{$paramId}'>";
                        
//                        $childrenText .= Chtml::radioButton("MenuItemsChk", $selected, $cHtmlOptions);
//                        $childrenText .= Chtml::label($param['label'], $paramId, array("class" => 'normal_label'));
                        
                        $childrenText .= $this->widget('amcwm.core.widgets.attachedItem.AttachedItemWidget', array(
                            'param' => $param,
                            'defaultValueData' => $select2Defaults
                        ), true);
                        
                        $childrenText .= "<div style='padding-bottom:5px;'>" . $param['description'] . "</div>";
                        $childrenText .= "</div>";
                        
                    } else {
                        $childrenText .= Chtml::checkBox($paramField, $selected, $cHtmlOptions);
                        $childrenText .= Chtml::label($param['label'], $paramId, array("class" => 'normal_label'));
                        $childrenText .= "<div>" . $param['description'] . "</div>";
                    }

                    $output .= $childrenText;
                }
                $output .= CHtml::closeTag('div');
            }

            if ($model->link != "" && $model->component_id == null)
                $model->component_id = "url";

            echo '<div class="menuItemsSelection"></div>';
            echo CHtml::openTag('div', array('class' => 'row'));
            echo $form->labelEx($model, 'component_id');
            echo $form->dropDownList($model, 'component_id', $options, array('style' => 'width:150px;', 'onchange' => 'showMyParams(this.value)'));
            echo $form->error($model, 'component_id');
            echo CHtml::closeTag('div');
            echo $output;
            ?>
        </fieldset>

        
        <fieldset>
            <?php
            $imagesInfo = $this->getModule()->appModule->mediaSettings;
            $drawImage = NULL;
            if ($model->item_id && $model->icon) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['path'] . "/" . $model->item_id . "." . $model->icon))) {
                    $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['path'] . "/" . $model->item_id . "." . $model->icon . "?" . time(), "", array("class" => "image")) . '</div>';
                }
            }
            ?>
            <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>       
            <div class="row">
                <?php echo $form->labelEx($model, 'iconImage'); ?>
                <?php echo $form->fileField($model, 'iconImage'); ?>
                <?php echo $form->error($model, 'iconImage'); ?>
            </div>
            
            <div id="itemImageFile">
                <?php echo $drawImage ?>
            </div>

            <?php if ($drawImage): ?>
            <div class="row">
                <input type="checkbox" name="deleteImage" id="deleteImage" style="float: right" onclick="deleteRelatedImage(this);" />
                <label for="deleteImage" id="lbldltimg" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                <label for="deleteImage" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklbl'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('displayDeleteImage', "
                    var imgDesc = null;
                    deleteRelatedImage = function(chk){
                        if(chk.checked){
                            if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                                jQuery('#chklbl').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                                imgDesc = jQuery('#imageDescription').val();
                                jQuery('#itemImageFile').slideUp();
                                jQuery('#imageDescription').val('');
                                jQuery('#lbldltimg').toggleClass('isChecked');
                            }else{
                                chk.checked = false;
                            }
                        }else{
                            jQuery('#chklbl').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                            jQuery('#imageDescription').val(imgDesc);
                            jQuery('#itemImageFile').slideDown();
                            jQuery('#lbldltimg').toggleClass('isChecked');
                        }
                    }    
                ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayImageCss', "
                    label#lbldltimg span {
                        display: none;
                    }
                    #deleteImage{
                        display: none;
                    }
                    label#lbldltimg {
                        background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                        width: 18px;
                        height: 18px;
                        display: block;
                        cursor: pointer;
                        float:right;
                        margin: 3px;
                    }
                    label#lbldltimg.isChecked {
                        background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                    }
                ");
            endif;
            ?>
        </fieldset>
        
        <?php if (isset($modelOptions['default']['check']['allowPageImage']) && $modelOptions['default']['check']['allowPageImage']):?>
        <fieldset>
            <?php
            $drawPageImage = NULL;
            if (isset($model->page_img) && $model->item_id && $model->page_img) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['pageImage']['path'] . "/" . $model->item_id . "." . $model->page_img))) {
                    $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['pageImage']['path'] . "/" . $model->item_id . "." . $model->page_img . "?" . time(), "", array("class" => "image", "width" => "200")) . '</div>';
                }
            }
            ?>
            <legend><?php echo AmcWm::t("msgsbase.core", "Page Image Options"); ?>:</legend>       
            <div class="row">
                <?php echo $form->labelEx($model, 'pageImg'); ?>
                <?php echo $form->fileField($model, 'pageImg'); ?>
                <?php echo $form->error($model, 'pageImg'); ?>
            </div>

            <div id="itemPageImageFile">
                <?php echo $drawImage ?>
            </div>

            <?php if ($drawImage): ?>
                <div class="row">
                    <input type="checkbox" name="deletePageImage" id="deletePageImage" style="float: right" onclick="deleteRelatedPageImage(this);" />
                    <label for="deletePageImage" id="lbldltPageimg" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                    <label for="deletePageImage" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklblPage'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeletePageImage', "
                    deleteRelatedPageImage = function(chk){
                        if(chk.checked){
                            if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                                jQuery('#chklblPage').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                                jQuery('#itemPageImageFile').slideUp();
                                jQuery('#lbldltPageimg').toggleClass('isChecked');
                            }else{
                                chk.checked = false;
                            }
                        }else{
                            jQuery('#chklblPage').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                            jQuery('#itemPageImageFile').slideDown();
                            jQuery('#lbldltPageimg').toggleClass('isChecked');
                        }
                    }    
                ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayPageImageCss', "
                    label#lbldltPageimg span {
                        display: none;
                    }
                    #deletePageImage{
                        display: none;
                    }
                    label#lbldltPageimg {
                        background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                        width: 18px;
                        height: 18px;
                        display: block;
                        cursor: pointer;
                        float:right;
                        margin: 3px;
                    }
                    label#lbldltPageimg.isChecked {
                        background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                    }
                ");
            endif;
            ?>
        </fieldset>
        <?php endif; // end if drow page image ?>
        
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->