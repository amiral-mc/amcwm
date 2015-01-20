<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $allOptions = $this->module->appModule->options;
    $options = null;
    if ($model->category) {
        $options = CJSON::decode($model->category->settings);
    }
    if (!$options) {
        $options = $allOptions['default'];
    }
    $mediaSettings = $this->module->appModule->mediaSettings;
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));

    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Company data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'accepted', array("style" => 'display:inline;', 'label'=>AmcWm::t("msgsbase.core", 'Status') . ":")); ?>
            <?php echo $form->dropDownList($model, 'accepted', $model->getStatus()); ?>

            <?php if ($options['check']['useTicker']): ?>
                <?php echo $form->checkBox($model, 'in_ticker'); ?>
                <?php echo $form->labelEx($model, 'in_ticker', array("style" => 'display:inline;')); ?>
            <?php endif; ?>
        </div>

        <?php if ($allOptions['system']['check']['categoriesEnable']): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'category_id'); ?>
                <?php echo $form->dropDownList($model, 'category_id', $model->getCategories(), array('prompt' => AmcWm::t("msgsbase.core", 'Select Category'))); ?>
                <?php echo $form->error($model, 'category_id'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'nationality'); ?>
            <?php echo $form->dropDownList($model, 'nationality', $this->module->appModule->getNationality(true), array('prompt' => AmcWm::t("msgsbase.core", 'Select Nationality'))); ?>
            <?php echo $form->error($model, 'nationality'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'company_name'); ?>
            <?php echo $form->textField($contentModel, 'company_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'company_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'activity'); ?>
            <?php echo $form->textField($contentModel, 'activity', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($contentModel, 'activity'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'company_address'); ?>
            <?php echo $form->extendableField($contentModel, 'company_address', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 150))); ?>
            <?php echo $form->error($contentModel, 'company_address'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'city'); ?>
            <?php echo $form->textField($contentModel, 'city', array('size' => 60, 'maxlength' => 150)); ?>
            <?php echo $form->error($contentModel, 'city'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->extendableField($model, 'email', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 65))); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'url'); ?>
            <?php echo $form->extendableField($model, 'url', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 65))); ?>
            <?php echo $form->error($model, 'url'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->extendableField($model, 'phone', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 65))); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'mobile'); ?>
            <?php echo $form->extendableField($model, 'mobile', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 65))); ?>
            <?php echo $form->error($model, 'mobile'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'fax'); ?>
            <?php echo $form->extendableField($model, 'fax', 'textField', array('htmlOptions' => array('size' => 60, 'maxlength' => 65))); ?>
            <?php echo $form->error($model, 'fax'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'description'); ?>
            <?php echo $form->error($contentModel, 'description'); ?>
            <?php echo $form->richTextField($contentModel, 'description', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>           
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "imagefile"); ?>:</legend>
        <div class="row">
            <?php if ($options['check']['imageEnable']): ?>
                <?php
                $drawImage = NULL;
                if ($model->company_id && $model->image_ext) {
                    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path'] . "/" . $model->company_id . "." . $model->image_ext))) {
                        $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['images']['path'] . "/" . $model->company_id . "." . $model->image_ext . "?" . time(), "", array("class" => "image", "style" => "max-width:100px")) . '</div>';
                    }
                }
                ?>                            
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
                    ?>            
                <?php endif; ?>
                <br />
                <div class="row">
                    <?php //echo $form->labelEx($model, 'imageFile'); ?>
                    <?php echo $form->fileField($model, 'imageFile'); ?>
                    <?php echo $form->error($model, 'imageFile'); ?>
                </div>
            <?php endif; ?>
        </div>
    </fieldset>

    <?php if ($options['check']['attachEnable']): ?>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Attach File"); ?>:</legend>

            <?php if ($model->company_id && $model->file_ext): ?>
                <div class="row">
                    <input type="checkbox" name="deleteFile" id="deleteFile" style="float: right" onclick="deleteRelatedFile(this);" />
                    <label for="deleteFile" id="labelDelFile" title=""><span><?php echo AmcWm::t("msgsbase.core", 'Delete Document'); ?></span></label>
                    <label for="deleteFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'><?php echo AmcWm::t("msgsbase.core", 'Delete File'); ?></span></label>
                    <label for="downlaodFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'> &nbsp;-&nbsp; <a href="<?php echo Html::createUrl('/site/download', array('f' => "{$mediaSettings['paths']['attach']['path']}/{$model->company_id}.{$model->file_ext}")) ?>"><?php echo AmcWm::t("msgsbase.core", 'Download file'); ?></a></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeleteFile', "
                        deleteRelatedFile = function(chk){
                            if(chk.checked){
                                if(confirm('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Are you sure you want to delete this file?')) . "')){
                                    jQuery('#checlFileLabel').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'undo delete file')) . "');
                                    jQuery('#labelDelFile').toggleClass('isChecked');
                                }else{
                                    chk.checked = false;
                                }
                            }else{
                                jQuery('#checlFileLabel').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Delete File')) . "');
                                jQuery('#labelDelFile').toggleClass('isChecked');
                            }
                        }    
                    ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayFileCss', "
                        label#labelDelFile span {
                            display: none;
                        }
                        #deleteFile{
                            display: none;
                        }
                        label#labelDelFile {
                            background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                            width: 18px;
                            height: 18px;
                            display: block;
                            cursor: pointer;
                            float:right;
                            margin: 3px;
                        }
                        label#labelDelFile.isChecked {
                            background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                        }
                    ");
            endif;
            ?><br />
            <div class="row">
                <?php // echo $form->labelEx($model, 'attachFile');  ?>
                <?php echo $form->fileField($model, 'attachFile'); ?>
                <?php echo $form->error($model, 'attachFile'); ?>
                <br /><br />
                <?php
                $mediaSettings = AmcWm::app()->appModule->mediaSettings;
                echo AmcWm::t('msgsbase.core', 'Files allowed "{files}"', array('{files}' => $mediaSettings['paths']['attach']['info']['extensions']))
                ?>
            </div>
        </fieldset>
    <?php endif; ?>

    <?php
    if ($options['check']['mapEnable']):
        $mapsData = null;
        $zoom = 1;
        $lat = '31.222197';
        $lng = '29.923095';
        $enabled = true;
        if ($model->maps) {
            $mapsData = CJSON::decode($model->maps);
            $zoom = (isset($mapsData['location']['zoom'])) ? $mapsData['location']['zoom'] : $zoom;
            $lat = (isset($mapsData['location']['lat'])) ? $mapsData['location']['lat'] : $lat;
            $lng = (isset($mapsData['location']['lng'])) ? $mapsData['location']['lng'] : $lng;
            $enabled = (isset($mapsData['location']['enabled'])) ? $mapsData['location']['enabled'] : $enabled;
        }

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
                        " .
                ($zoom != 1 ? "marker = new google.maps.Marker({
                                      position: selectedLocation,
                                      map: map
                                });" : "")
                . "
                        google.maps.event.addListener(map, 'click', function(e) {
                            if(!marker){
                                marker = new google.maps.Marker({
                                      position: e.latLng,
                                      map: map
                                });
                            }else{
                                marker.setPosition(e.latLng);
                            }
                            
                            jQuery('#lat').val(e.latLng.lat());
                            jQuery('#lng').val(e.latLng.lng());
                            jQuery('#zoom').val(map.getZoom());
                            map.panTo(e.latLng);
                        });
                    }
              google.maps.event.addDomListener(window, 'load', initialize);";

        Yii::app()->getClientScript()
                ->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', CClientScript::POS_HEAD)
                ->registerScript('__GMAP', $js, CClientScript::POS_READY);
        ?>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Upload Map"); ?>:</legend>
            <div class="row">
                <?php echo AmcWm::t("msgsbase.core", "You can upload a location map, and/or add its location coordinates on google maps"); ?>
                <?php
                $drawMap = NULL;
                if ($model->company_id && isset($mapsData['image'])) {
                    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['maps']['path'] . "/" . $model->company_id . "." . $mapsData['image']))) {
                        $drawMap = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['maps']['path'] . "/" . $model->company_id . "." . $mapsData['image'] . "?" . time(), "", array("class" => "image", "style" => "max-width:100px")) . '</div>';
                    }
                }
                ?>
                <div id="itemMapFile">
                    <?php echo $drawMap ?>
                </div>
                <?php if ($drawMap): ?>
                    <div class="row">
                        <input type="checkbox" name="deleteMap" id="deleteMap" style="float: right" onclick="deleteRelatedImage(this);" />
                        <label for="deleteMap" id="lbldltmap" title=""><span><?php echo AmcWm::t("msgsbase.core", 'Delete Map'); ?></span></label>
                        <label for="deleteMap" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklblmap'><?php echo AmcWm::t("msgsbase.core", 'Delete Map'); ?></span></label>
                    </div>
                    <?php
                    Yii::app()->clientScript->registerScript('displayDeleteImage', "
                        var imgDesc = null;
                        deleteRelatedImage = function(chk){
                            if(chk.checked){
                                if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                                    jQuery('#chklblmap').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'undo delete map')) . "');
                                    jQuery('#itemMapFile').slideUp();
                                    jQuery('#lbldltmap').toggleClass('isChecked');
                                }else{
                                    chk.checked = false;
                                }
                            }else{
                                jQuery('#chklblmap').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Delete Map')) . "');
                                jQuery('#itemMapFile').slideDown();
                                jQuery('#lbldltmap').toggleClass('isChecked');
                            }
                        }    
                    ", CClientScript::POS_HEAD);

                    Yii::app()->clientScript->registerCss('displayImageCss', "
                        label#lbldltmap span {
                            display: none;
                        }
                        #deleteMap{
                            display: none;
                        }
                        label#lbldltmap {
                            background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                            width: 18px;
                            height: 18px;
                            display: block;
                            cursor: pointer;
                            float:right;
                            margin: 3px;
                        }
                        label#lbldltmap.isChecked {
                            background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                        }
                    ");
                    ?>            
                <?php endif; ?>
                <br />
                <div class="row">                        
                    <?php echo $form->fileField($model, 'mapFile'); ?>
                    <?php echo $form->error($model, 'mapFile'); ?>
                </div>
                <?php echo AmcWm::t("msgsbase.core", "Locate the position on the map by clicking on the company location"); ?>
                <div class="row">
                    <div id="map-canvas" style="width: 100%; height: 300px;"></div>
                    <?php
                    echo CHtml::hiddenField('lat', $lat);
                    echo CHtml::hiddenField('lng', $lng);
                    echo CHtml::hiddenField('zoom', $zoom);

                    echo CHtml::checkBox('enabled', $enabled);
                    echo CHtml::label(AmcWm::t('msgsbase.core', 'View Google map'), 'enabled', array("style" => 'display:inline;'));
                    ?>
                </div>
            </div>
        </fieldset>
    <?php endif; ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->