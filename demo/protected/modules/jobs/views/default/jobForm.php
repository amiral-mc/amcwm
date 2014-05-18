<style type="text/css">
    div.form label.normal-label{
        display: inline !important;
    }
    div.form .row{
        margin: 10px;
    }
</style>
<div class="form">
    <?php
    $form = $this->beginWidget('Form', array(
        'id' => 'jobrequest-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));

    $form->hiddenField($model, 'job_id');
    ?>

    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone', array('size' => 10, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'city'); ?>
        <?php echo $form->textField($model, 'city', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'city'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'address'); ?>
        <?php echo $form->textArea($model, 'address', array('style'=>'width:450px')); ?>
        <?php echo $form->error($model, 'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'nationality'); ?>
        <?php echo $form->dropDownList($model, 'nationality', $this->getCountries(TRUE), array('prompt' => AmcWm::t('msgsbase.request', 'Your Nationality'))); ?>
        <?php echo $form->error($model, 'nationality'); ?>
    </div>

    <div class="row">
        <?php //echo $form->labelEx($model, 'job_id'); ?>
        <?php //echo $form->dropDownList($model, 'job_id', $this->getJobsList(), array('prompt'=>AmcWm::t('msgsbase.request', 'Apply for job'))); ?>
        <?php //echo $form->error($model, 'job_id'); ?>
    </div>

    <?php
    $militaryStatus = array(
        '' => AmcWm::t('msgsbase.request', 'Your military status'),
        '0' => AmcWm::t('msgsbase.request', 'Completed'),
        '1' => AmcWm::t('msgsbase.request', 'Exempted'),
        '2' => AmcWm::t('msgsbase.request', 'Does Not Apply'),
        '3' => AmcWm::t('msgsbase.request', 'Currently Serving'),
        '4' => AmcWm::t('msgsbase.request', 'Postponed'),
    );

    $maritalStatus = array(
        '' => AmcWm::t('msgsbase.request', 'Your marital status'),
        '0' => AmcWm::t('msgsbase.request', 'Single'),
        '1' => AmcWm::t('msgsbase.request', 'Married'),
        '2' => AmcWm::t('msgsbase.request', 'Separated'),
        '3' => AmcWm::t('msgsbase.request', 'Divorced'),
    );

    $yes_no = array(
        '0' => AmcWm::t('msgsbase.request', 'No'),
        '1' => AmcWm::t('msgsbase.request', 'Yes'),
    );
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'sex'); ?>
        <?php echo $form->radioButtonList($model, 'sex', array('M' => AmcWm::t('msgsbase.request', 'Male'), 'F' => AmcWm::t('msgsbase.request', 'Female')), array("separator" => "&nbsp;", 'labelOptions' => array('class' => 'normal-label'))); ?>  
        <?php echo $form->error($model, 'sex'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'car_owner', array('style' => 'normal-label')); ?>
        <?php echo $form->radioButtonList($model, 'car_owner', $yes_no, array("separator" => "&nbsp;", 'labelOptions' => array('class' => 'normal-label'))); ?>
    </div>
    
    <div class="row">  
        <?php echo $form->labelEx($model, 'driving_license', array('style' => 'normal-label')); ?>
        <?php echo $form->radioButtonList($model, 'driving_license', $yes_no, array("separator" => "&nbsp;", 'labelOptions' => array('class' => 'normal-label'))); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'have_children', array('style' => 'normal-label')); ?>
        <?php echo $form->radioButtonList($model, 'have_children', $yes_no, array("separator" => "&nbsp;", 'labelOptions' => array('class' => 'normal-label'))); ?>        
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'marital'); ?>
        <?php echo $form->dropDownList($model, 'marital', $maritalStatus); ?>
        <?php echo $form->error($model, 'marital'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'military'); ?>
        <?php echo $form->dropDownList($model, 'military', $militaryStatus); ?>
        <?php echo $form->error($model, 'military'); ?>
    </div>
    
    <div class="row">
        <?php //echo $form->labelEx($model, 'date_of_birth'); ?>
        <?php //echo $form->calendarField($model, 'date_of_birth', array('dateOptions' => array('dateOnly' => true))); ?>
        <?php //echo $form->error($model, 'date_of_birth'); ?>
        <?php
        $years = $months = $days = array();
        for ($i = 1940; $i <= 1990; $i++) {
            $years[$i] = $i;
        }
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $i;
        }
        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }
        ?>
        <?php echo $form->labelEx($model, 'date_of_birth'); ?>
        <?php echo $form->dropDownList($model, 'dobYear', $years, array('prompt' => AmcWm::t('msgsbase.request', 'Year'))); ?>
        <?php echo $form->dropDownList($model, 'dobMonth', $months, array('prompt' => AmcWm::t('msgsbase.request', 'Month'))); ?>
        <?php echo $form->dropDownList($model, 'dobDay', $days, array('prompt' => AmcWm::t('msgsbase.request', 'Day'))); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'educations'); ?>
        <?php echo $form->textArea($model, 'educations', array('rows' => 3, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'educations'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'work_experiences'); ?>
        <?php echo $form->textArea($model, 'work_experiences', array('rows' => 3, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'work_experiences'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'computer_skills'); ?>
        <?php echo $form->textArea($model, 'computer_skills', array('rows' => 3, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'computer_skills'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'professional_certifications'); ?>
        <?php echo $form->textArea($model, 'professional_certifications', array('rows' => 3, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'professional_certifications'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'career_objective'); ?>
        <?php echo $form->textArea($model, 'career_objective', array('rows' => 3, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'career_objective'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'attachedFile'); ?>
        <?php echo $form->fileField($model, 'attachedFile'); ?>
        <?php echo $form->error($model, 'attachedFile'); ?>
    </div>

    <?php if (CCaptcha::checkRequirements()): ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'verifyCode'); ?>
            <div>
                <?php echo $form->textField($model, 'verifyCode'); ?>
                <?php $this->widget('CCaptcha'); ?>
            </div>
            <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
            <?php echo $form->error($model, 'verifyCode'); ?>
        </div>
    <?php endif; ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('msgsbase.request', 'Save')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->