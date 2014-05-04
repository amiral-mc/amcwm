<div class="form">    
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            )
    );
    
   /*
    * debricated, will be removed..
    */
//    if(count($uploadedFileInfo)){
//        if(isset($uploadedFileInfo['type']) && $uploadedFileInfo['type'] == AttachmentList::IMAGE){
//            echo "<div>";
//            echo CHtml::image($uploadedFileInfo['url'], '', array('style'=>'max-width:100px;'));
//            echo "</div>";
//        }
//    }
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php
    echo $form->errorSummary($model);
//    $fileInfo = $model->uploadedFileInfo;
    echo CHtml::hiddenField('op', Yii::app()->request->getParam('op'));
    
//    if ($fileInfo) {
//        echo '<div class="file-container">';
//        echo '<div class="file-box" data-type="' . $fileInfo['type'] . '" data-url="' . $fileInfo['url'] . '">';
//        switch ($fileInfo['type']) {
//            case AttachmentList::IMAGE:
//                echo '<div class="file-box-img"><img src= "' . $fileInfo['url'] . '" title="' . CHtml::encode($fileInfo['title']) . '" /></div>';
////                    echo '<span>' . $fileInfo['title'] . '</span>';
//                break;
//            case AttachmentList::INTERNAL_VIDEO:
//                echo '<div class="file-box-internal">' . $fileInfo['title'] . '</div>';
//                break;
//            case AttachmentList::LINK:
//                echo '<div class="file-box-internal">' . $fileInfo['title'] . '</div>';
//                break;
//        }
//        echo '</div>';
//        echo '</div>';
//    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'file'); ?>
        <?php echo $form->fileField($model, 'file'); ?>
        <?php echo $form->error($model, 'file'); ?>
    </div>
    <div class="row buttons">                        
        <?php echo CHtml::submitButton(AmcWm::t("msgsbase.core", '_upload_'), array("style" => "width:60px;")); ?>
    </div>         	
    <?php $this->endWidget(); ?>
</div>