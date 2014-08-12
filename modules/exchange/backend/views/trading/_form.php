<div class="form">
    <?php
    $companiesCount = count($model->tradingCompanies);
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <div class="row">
            <?php echo $form->hiddenField($model, 'exchange_id', array('value' => $eid)); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'exchange_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'exchange_date',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => false,
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'style' => 'direction:ltr',
                    'readonly' => 'readonly',
                    'value' => ($model->exchange_date) ? date("Y-m-d", strtotime($model->exchange_date)) : date("Y-m-d"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'exchange_date'); ?>
        </div>
        <div class="row">                       
            <?php echo $form->labelEx($model, 'trading_value'); ?>
            <?php echo $form->textField($model, 'trading_value'); ?>
            <?php echo $form->error($model, 'trading_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'shares_of_stock'); ?>
            <?php echo $form->textField($model, 'shares_of_stock'); ?>
            <?php echo $form->error($model, 'shares_of_stock'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'closing_value'); ?>
            <?php echo $form->textField($model, 'closing_value'); ?>
            <?php echo $form->error($model, 'closing_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'difference_value'); ?>
            <?php echo $form->textField($model, 'difference_value'); ?>
            <?php echo $form->error($model, 'difference_value'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'difference_percentage'); ?>
            <?php echo $form->textField($model, 'difference_percentage'); ?>
            <?php echo $form->error($model, 'difference_percentage'); ?>
        </div>
    </fieldset>
    <?php // if (isset($companies) && $companies) { ?>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.companies", "Companies"); ?></legend>
            <div>
                <table cellpadding="2" cellspasing ="0" id="companyGrid">
                    <tr>
                        <th></th>
                        <th><?php echo AmcWm::t("msgsbase.companies", "Company Name"); ?></th>
                        <th><?php echo AmcWm::t("msgsbase.companies", "Opening Value"); ?></th>
                        <th><?php echo AmcWm::t("msgsbase.companies", "Closing Value"); ?></th>
                        <th><?php echo AmcWm::t("msgsbase.companies", "Difference %"); ?></th>

                    </tr>
                 
                    <?php foreach ($model->tradingCompanies as $key => $tradingsModel): ?>
                        <tr id="companyRow<?php echo $key ?>">
                            <td valign="top"><?php echo $form->labelEx($model, "($key)"); ?> </td>
                            <td valign="top"><?php echo $form->dropDownList($tradingsModel, "[$key]exchange_companies_exchange_companies_id", ExchangeCompanies::getCompanies($eid), array('style' => 'width:100px', 'prompt' => AmcWm::t("msgsbase.companies", 'Select Company'))); ?></td>
                            <td valign="top"><?php echo $form->textField($tradingsModel, "[$key]opening_value", array('style' => 'width:100px')); ?></td>
                            <td valign="top"><?php echo $form->textField($tradingsModel, "[$key]closing_value", array('style' => 'width:100px')); ?></td>
                            <td valign="top"><?php echo $form->textField($tradingsModel, "[$key]difference_percentage", array('style' => 'width:100px')); ?></td>
                            <td valign="top">
                                <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("id" => "companyRowLink{$key}", "onclick" => "company.remove(this.id)", "class" => "btn_label")); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div style="text-align: right;">
                    <?php
                    echo CHtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . AmcWm::t("msgsbase.companies", "New Company"), "javascript:void(0);", array("id" => "newCompany", "class" => "btn_label"));
                    ?>
                </div>
            </div>
        </fieldset>
    <?php // } ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('companiesManager', "
    var count = 0;
    company = {};
    company.options = {};
    company.name = " . CJSON::encode(ExchangeCompanies::getCompanies($eid, true)) . ";
    company.check = function(){
        var table = document.getElementById('companyGrid');
        for (var i = 0, row; row = table.rows[i]; i++) {
            if(table.rows[i].style.display != 'none'){
                count++;
            }
        }
        if(count > {$companiesCount}){
            $('#newCompany').hide();
        }
        else{
            $('#newCompany').show();
        }
    }
    company.check();
    
    $('#newCompany').click(function(){    
        company.add();
        return false;
    });    
    company.hideShowDeleteIcon = function(ref, checked){
    removeNumber = parseInt(ref.substring(13));
        if(checked){
            $('#companyRowLink'+removeNumber).hide();        
        }
        else{
            $('#companyRowLink'+removeNumber).show();        
        }
    }
    company.add = function(){
        count = 0;
        lastRow = ($('#companyGrid tr').length -1);
        var companyRow = '<tr id=\"companyRow'+lastRow+'\">';
        companyRow += '<td valign=\"top\">(' + $('#companyGrid').find('tr').index() + ')</td>';
        companyRow += '<td valign=\"top\">';
        companyRow += '<select name=\"ExchangeTradingCompanies['+lastRow+'][exchange_companies_exchange_companies_id]\" id=\"ExchangeTradingCompanies_'+lastRow+'_type\">';
        companyRow += '<option value=\"\">" . AmcWm::t("msgsbase.companies", 'Select Company') . "</option>';
        for(var typeRef =0 ; typeRef < company.name.length ; typeRef++){
            companyRow += '<option value=\"'+company.name[typeRef].exchange_companies_id+'\">'+company.name[typeRef].company_name+'</option>';
        }
        companyRow += '</select>';
        companyRow += '</td>';
        companyRow += '<td><input type=\"text\" name=\"ExchangeTradingCompanies['+lastRow+'][opening_value]\" style=\"width:100px;\"></td>';
        companyRow += '<td><input type=\"text\" name=\"ExchangeTradingCompanies['+lastRow+'][closing_value]\" style=\"width:100px;\"></td>';
        companyRow += '<td><input type=\"text\" name=\"ExchangeTradingCompanies['+lastRow+'][difference_percentage]\" style=\"width:100px;\"></td>';
        companyRow +='<td valign=\"top\"><a id=\"companyRowLink'+lastRow+'\" onclick=\"company.remove(this.id)\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" /></td>';
        companyRow += '</tr>';
        $('#companyGrid').append(companyRow);
        company.check();
    }
    company.remove = function(companyRowId){
        count = 0;
        removeNumber = companyRowId.substring(14);
        $('#companyRow'+removeNumber).html('<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>');
        $('#companyRow'+removeNumber).hide();
        company.check();
    }
    
");
?>