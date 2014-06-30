<div class="form">    
    <?php
    $alloUpdate = !$model->integrityCheck();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    if ($model->user_id) {
        $user = "[{$model->user->person->getCurrent()->name}]";
        if ($model->user->person->email) {
            $user .= " [{$model->user->person->email}]";
        }
    } else {
        $user = null;
    }
    ?>    
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php
    echo $form->errorSummary(
            array(
                $model,
                $model->getReceptionSea(),
                $model->getReceptionAir(),
                $model->getPortDestination(),
                $model->getDestinationInfo()
            )
    );
    ?>    
    <fieldset>
        <div class="row">
            <?php if ($alloUpdate): ?>
                <?php echo $form->labelEx($model, 'create_date'); ?>
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'create_date',
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
                        'value' => ($model->create_date) ? date("Y-m-d H:i", strtotime($model->create_date)) : date("Y-m-d H:i"),
                    )
                ));
                ?>
                <?php echo $form->error($model, 'create_date'); ?>
            <?php else: ?>
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Date'); ?></span>:
                <span class="translated_org_item"><?php echo Yii::app()->dateFormatter->format("HH:mm | dd-MM-y", $model->create_date); ?></span>
            <?php endif; ?>
        </div>
        <?php if ($alloUpdate): ?>
            <div class="row">
                <?php
                $this->widget('TabView', array(
                    'activeTab' => "reception_{$model->receptionType}",
                    'useCustomCSS' => false,
                    'tabs' => array(
                        'reception_1' => array(
                            'title' => AmcWm::t("msgsbase.core", "Sea Parcel"),
                            'view' => '_reception_sea',
                            'data' => array('form' => $form, 'model' => $model->getReceptionSea()),
                        ),
                        'reception_2' => array(
                            'title' => AmcWm::t("msgsbase.core", "Air Parcel"),
                            'view' => '_reception_air',
                            'data' => array('form' => $form, 'model' => $model->getReceptionAir()),
                        ),
                    ),
                    'htmlOptions' => array(
                        'style' => 'width:600px;'
                    )
                ));
                ?>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('receptionTabs', "
                        $('#reception_1Link').click(function(){
                            $('#receptionType').val('" . Parcels::RECEPTION_BY_SEA . "');
                            return false;
                        });
                        $('#reception_2Link').click(function(){
                            $('#receptionType').val('" . Parcels::RECEPTION_BY_AIR . "');
                            return false;
                        });
                    ");
            echo $form->hiddenField($model, 'receptionType', array('id' => 'receptionType'));
            ?>
            <div class="row">
                <?php
                $this->widget('TabView', array(
                    'activeTab' => "delivered_{$model->deliveredType}",
                    'useCustomCSS' => false,
                    'tabs' => array(
                        'delivered_1' => array(
                            'title' => AmcWm::t("msgsbase.core", "Delivered to vessel"),
                            'view' => '_delivered_vessel',
                            'data' => array('form' => $form, 'model' => $model->getPortDestination()),
                        ),
                        'delivered_2' => array(
                            'title' => AmcWm::t("msgsbase.core", "Others"),
                            'view' => '_delivered_others',
                            'data' => array('form' => $form, 'model' => $model->getDestinationInfo()),
                        ),
                    ),
                    'htmlOptions' => array(
                        'style' => 'width:600px;'
                    )
                ));
                ?>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('deliveredTabs', "
                        $('#delivered_1Link').click(function(){
                            $('#deliveredType').val('" . Parcels::DELIVERED_TO_VESSEL . "');
                            return false;
                        });
                        $('#delivered_2Link').click(function(){
                            $('#deliveredType').val('" . Parcels::DELIVERED_TO_OTHERS . "');
                            return false;
                        });
                    ");
            echo $form->hiddenField($model, 'deliveredType', array('id' => 'deliveredType'));
            ?>
        <?php else: ?>            
                <?php
                $attributes = array();
                if ($model->receptionType == Parcels::RECEPTION_BY_SEA) {
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Vessel Eta'),
                        'value' => $model->getReceptionSea()->eta->getEtaLabel(),
                    );
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Bill Of Lading'),
                        'value' => $model->getReceptionSea()->bill_of_lading,
                    );
                } else if ($model->receptionType) {
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Shipping Company / Airway Name'),
                        'value' => $model->getReceptionAir()->carrier->getCurrent()->carrier_name,
                    );
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'AWB Number'),
                        'value' => $model->getReceptionAir()->awb_number,
                    );
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Flight Description'),
                        'value' => $model->getReceptionAir()->flight_description,
                    );
                }
                if ($model->deliveredType == Parcels::DELIVERED_TO_VESSEL) {
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Vessel Eta'),
                        'value' => $model->getPortDestination()->eta->getEtaLabel(),
                    );
                    $portName = ($model->getPortDestination()->deliveryPort) ? $model->getPortDestination()->deliveryPort->port_code . ": " . $model->getPortDestination()->deliveryPort->getCurrent()->port_name : AmcWm::t("msgsbase.core", "ETA Port");
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Delivery Port'),
                        'value' => $portName,
                    );
                } else if ($model->deliveredType == Parcels::DELIVERED_TO_OTHERS) {
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Name'),
                        'value' => $model->getDestinationInfo()->name,
                    );
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Address'),
                        'value' => $model->getDestinationInfo()->address,
                    );
                    $attributes[] = array(
                        'label' => AmcWm::t("msgsbase.core", 'Delivery to'),
                        'value' => $model->getDestinationInfo()->getDeliveryLabel($model->getDestinationInfo()->type),
                    );
                }
                foreach ($attributes as $attribute){
                    echo '<div class="row">';
                    echo '<span class="translated_label">';
                    echo  "{$attribute['label']}: ";
                    echo '</span>';                    
                    echo '<span class="translated_org_item">';
                    echo $attribute['value'];
                    echo '</span>';
                    echo '</div>';
                }
                ?>
        <?php endif; ?>
        <div class="row">            
            <?php echo $form->labelEx($model, 'user_id'); ?>
            <?php
            $initUserSelection = ($user) ? array('id' => $model->user_id, 'text' => $user) : array();
            $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                'model' => $model,
                'attribute' => 'user_id',
                'addingNoMatch' => false,
                'initSelection' => $initUserSelection,
                'options' => array(
                    "dropdownCssClass" => "bigdrop",
                    'ajax' => array(
                        'dataType' => "json",
                        "quietMillis" => 100,
                        'url' => Html::createUrl('/backend/parcels/default/ajax', array('do' => 'usersList',)),
                        'data' => 'js:function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                               q: term, //search term
                               page: page, // page number                     
                           };
                       }',
                        'results' => 'js:function (data, page) {
                            var more = (page * ' . Parcels::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                            // notice we return the value of more so Select2 knows if more results can be loaded
                            return {results: data.records, more: more};
                          }',
                    ),
                ),
                'htmlOptions' => array(
                    'style' => 'min-width:400px;',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'user_id'); ?>
        </div>              
        <div class="row">
            <?php if ($alloUpdate): ?>
            <?php echo $form->labelEx($model, 'weight'); ?>
            <?php echo $form->textField($model, 'weight', array('size' => 7, 'maxlength' => 9)); ?>
            <?php echo $form->error($model, 'weight'); ?>
            <?php else: ?>
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Weight'); ?></span>:
                <span class="translated_org_item"><?php echo$model->weight; ?></span>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description'); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'remarks'); ?>
            <?php echo $form->textArea($model, 'remarks'); ?>
            <?php echo $form->error($model, 'remarks'); ?>
        </div>        
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->