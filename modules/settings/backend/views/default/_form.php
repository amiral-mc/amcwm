<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php
        $tabs = array();
        if (count($configProperties)) {

            $languages = AmcWm::app()->params['languages'];

            foreach ($languages as $lang => $name) {
                $element = "";
                foreach ($configProperties as $c) {
                    if ($c['visible']) {
                        $element .= "<div id='row'>"
                                . $form->labelEx($model, AmcWm::t("msgsbase.core", $c["name"]), $c['htmlOptions'])
                                . $form->$c['type']($model, 'configProperties[' . $lang . '][' . $c['name'] . ']', $c['htmlOptions'])
                                . $form->error($model, 'configProperties[' . $lang . '][' . $c['name'] . ']')
                                . "</div>";
                    }
                }

                $tabs[$lang]["title"] = $name;
                $tabs[$lang]["content"] = $element;
            }

            $this->widget('TabView', array(
                'useCustomCSS' => false,
                'tabs' => $tabs
            ));
        }
        ?>            
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->