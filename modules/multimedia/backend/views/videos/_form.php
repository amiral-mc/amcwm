<div class="form">
    <?php
    $mediaPaths = $this->getModule()->appModule->mediaPaths;
    $model = $contentModel->getParentContent();
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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <div>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
            <div class="row">                       
                <span class="translated_label">
                    <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
                </span>
                :
                <span class="translated_org_item">
                    <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
                </span>
            </div>              
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>

            <?php echo $form->checkBox($model, 'in_slider'); ?>
            <?php echo $form->labelEx($model, 'in_slider', array("style" => 'display:inline;')); ?>         
        </fieldset>
    </div>
    <div>

        <?php
        $drawImage = $thumbImage = null;
        if (!$model->isNewRecord && isset($model->internalVideos->img_ext)) {
            $drawImage = Yii::app()->baseUrl . "/" . $mediaPaths['videos']['thumb']['path'] . "/" . $model->video_id . "." . $model->internalVideos->img_ext . "?t=" . time();
            $drawImage = str_replace("{gallery_id}", $model->gallery_id, $drawImage);
            $thumbImage = Chtml::image($drawImage, "", array("width" => 100));
        }
        $internalUrlOptions = array('dir' => 'ltr');
        if (trim($model->videoURL) == "") {
            //$internalUrlOptions['value'] = "http://www.youtube.com/watch?v=";
        }
        ?>

        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Video Options"); ?>:</legend>
            <?php //echo AmcWm::t("msgsbase.core", "Add either external video or upload a new video file, with its thumb view"); ?>
            <div style="padding: 5px;">
                <?php
                $video = NULL;
                if ($model->videoType == "externalVideos") {
                    $video = $model->videoURL;
                } else if ($model->videoType == "internalVideos" && $model->internalVideos !== NULL) {
                    $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/" . $model->video_id . "." . $model->internalVideos->video_ext;
                    $videoFile = str_replace("{gallery_id}", $model->gallery_id, $videoFile);
                    if (is_file($videoFile)) {
                        $video = Yii::app()->request->baseUrl . "/" . $mediaPaths['videos']['path'] . "/{$model->internalVideos->video_id}.{$model->internalVideos->video_ext}";
                        $video = str_replace("{gallery_id}", $model->gallery_id, $video);
                    }
                }
                //die($video);
                if ($video) {
                    $this->widget('amcwm.widgets.videoplayer.VideoPlayer', array(
                        'id' => 'videoPlayer1',
                        'className' => 'videoPlayerClass',
                        'width' => 300,
                        'height' => 200,
                        'title' => 'Your video header text',
                        'video' => $video,
                            )
                    );
                }
                ?>
            </div>
            <?php
            if (is_null($model->videoType)) {
                $model->videoType = 'externalVideos';
            }

            $videoMaxSize = $mediaPaths['videos']['info']['size'] / 1024 / 1024;
            $externalVideoTab = "";
            $firstContentLang = $model->getFirstInsertedLang();
            if ($this->getModule()->appModule->youtubeApiIsEnabled() && (!$firstContentLang || $firstContentLang == $contentModel->content_lang)) {
                $externalVideoTab .=
                        "<div id='row'>"
                        . $form->labelEx($model, 'youtubeFile')
                        . $form->fileField($model, 'youtubeFile')
                        . $form->error($model, 'youtubeFile')
                        . "<br />" . AmcWm::t("msgsbase.core", "Videos Supported {extensions}", array("{extensions}" => '<span class="required" style="direction:ltr; text-align:left">*.wmv, *.flv</span>'))
                        . "</div>";
            }
            $externalVideoTab .=
                    "<div id='row'>"
                    . $form->labelEx($model, 'videoURL')
                    . $form->textField($model, 'videoURL', $internalUrlOptions)
                    . $form->error($model, 'videoURL')
                    . '<div class="note">' . AmcWm::t("msgsbase.core", 'Example') . ': http://www.youtube.com/watch?v=<span style="color:#cc0000">EtMcV61PhDQ</span></div>'
                    . "</div>";
            $this->widget('TabView', array(
                'activeTab' => ($model->videoType == Videos::EXTERNAL) ? Videos::EXTERNAL : Videos::INTERNAL,
                'useCustomCSS' => false,
                'tabs' => array(
                    'externalVideos' => array(
                        'title' => AmcWm::t("msgsbase.core", "External Video"),
                        //'title' => AmcWm::t("msgsbase.core", "Youtube video File"),
                        'content' => $externalVideoTab,
                    ),
                    'internalVideos' => array(
                        'title' => AmcWm::t("msgsbase.core", "Internal Video"),
                        'content' => "<div id='row'>"
                        . $form->labelEx($model, 'videoFile')
                        . $form->fileField($model, 'videoFile')
                        . $form->error($model, 'videoFile')
                        . "<br />" . AmcWm::t("msgsbase.core", "Video File notes")
                        . $videoMaxSize . "M"
                        . "<br /><br /></div>"
                        . "<div id='row'>"
                        . $form->labelEx($model, 'videoThumb')
                        . $form->fileField($model, 'videoThumb')
                        . $form->error($model, 'videoThumb')
                        . "<br />" . AmcWm::t("msgsbase.core", "Video Thumb notes")
                        . "<br /><br />" . $thumbImage
                        . "</div>"
                    ),
                ),
                'htmlOptions' => array(
                    'style' => 'width:600px;'
                )
            ));

            Yii::app()->clientScript->registerScript('videosTabs', "
                        $('#externalVideosLink').click(function(){
                            $('#videoType').val('externalVideos');
                            return false;
                        });
                        $('#internalVideosLink').click(function(){
                            $('#videoType').val('internalVideos');
                            return false;
                        });
                    ");

            echo $form->hiddenField($model, 'videoType', array('id' => 'videoType'));
            ?>            
        </fieldset>
    </div>
    <div>
        <fieldset>
            <div class="row">
                <?php echo $form->labelEx($model, 'gallery_id'); ?>
                <?php echo $form->dropDownList($model, 'gallery_id', Galleries::getGalleriesList()); ?>
                <?php echo $form->error($model, 'gallery_id'); ?>
            </div>
            <?php if ($this->getModule()->appModule->useInfocus): ?>
                <div class="row">
                    <?php echo $form->labelEx($model, 'infocusId'); ?>
                    <?php echo $form->dropDownList($model, 'infocusId', $this->getInfocus()); ?>
                    <?php echo $form->error($model, 'infocusId'); ?>
                </div>            
            <?php endif; ?>
            <div class="row">
                <?php echo $form->labelEx($contentModel, 'video_header'); ?>
                <?php echo $form->textField($contentModel, 'video_header', array('size' => 60, 'maxlength' => 500)); ?>
                <?php echo $form->error($contentModel, 'video_header'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($contentModel, 'description'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $contentModel,
                    'attribute' => 'description',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:600px;'
                    ),
                        )
                );
                ?>
                <?php echo $form->error($contentModel, 'description'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'publish_date'); ?>
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'publish_date',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'timeFormat' => 'hh:mm',
                        'changeMonth' => true,
                        'changeYear' => false,
                    ),
                    'htmlOptions' => array(
                        'class' => 'datebox',
                        'style' => 'direction:ltr',
                        'readonly' => 'readonly',
                        'value' => ($model->publish_date) ? date("Y-m-d H:i", strtotime($model->publish_date)) : date("Y-m-d 00:01"),
                    )
                ));
                ?>
                <?php echo $form->error($model, 'publish_date'); ?>

            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'expire_date'); ?>                        
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'expire_date',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'timeFormat' => 'hh:mm',
                        'changeMonth' => true,
                        'changeYear' => false,
                    ),
                    'htmlOptions' => array(
                        'class' => 'datebox',
                        'style' => 'direction:ltr',
                        'readonly' => 'readonly',
                        'value' => ($model->expire_date) ? date("Y-m-d H:i", strtotime($model->expire_date)) : NULL,
                    )
                ));
                ?>            
                <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Videos_expire_date").val("")')) ?>
                <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
                <?php echo $form->error($model, 'expire_date'); ?>
            </div>


        </fieldset>

        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
            <div class="row">
                <?php
                $this->widget('Keywards', array(
                    'model' => $contentModel,
                    'attribute' => "tags[]",
                    //                    'name' => "tags",
                    'values' => $contentModel->tags,
                    'formId' => $formId,
                    'container' => "keywordItems",
                    'delimiter' => Yii::app()->params["limits"]["delimiter"],
                    'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                    'wordsCount' => Yii::app()->params["limits"]["wordsCount"], //  words in each box count
                    'htmlOptions' => array(),
                        )
                );
                ?>            
            </div>     
        </fieldset>
    </div>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>            
            <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
            <?php echo $form->error($model, 'socialIds'); ?>
        </fieldset>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->