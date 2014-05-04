<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcConfigurationController extends BackendController {
    
    public function actionIndex() {
        $this->render('view');
    }

    public function actionUpdate() {
        $model = new ConfigurationForm();

        $configProperties = AmcWm::app()->params['configProperties'];
        $languages = AmcWm::app()->params['languages'];
        $arrayData = array();
        foreach ($languages as $lang => $name) {
            $confQuery = sprintf("select config from configuration where content_lang = '$lang'");
            $confData = AmcWm::app()->db->createCommand($confQuery)->queryScalar();

            $confDataArray = unserialize(base64_decode($confData));
            foreach ($configProperties as $c) {
                if (isset($confDataArray['custom']['front']['site'][$c["name"]])) {
                    $arrayData[$lang][$c["name"]] = $confDataArray['custom']['front']['site'][$c["name"]];
                }
            }
        }

        $model->configProperties = $arrayData;

        if (isset($_POST['ConfigurationForm'])) {
            $model->attributes = $_POST['ConfigurationForm'];
            if ($model->validate()) {

                $confQueryNew = array();
                foreach ($languages as $lang => $name) {
                    
                    $formElements = $_POST["ConfigurationForm"]["configProperties"][$lang];
                    if (count($formElements)) {
                        foreach ($formElements as $element => $value) {
                            $confDataArray['custom']['front']['site'][$element] = $value;
                        }
                        $config = base64_encode(serialize($confDataArray));
                    }else{
                        $config = '';
                    }
                    

                    $confQuery = sprintf("select count(*) from configuration where content_lang = '$lang'");
                    $issetData = AmcWm::app()->db->createCommand($confQuery)->queryScalar();
                    if($issetData == 0){
                        $confQueryNew[] = sprintf("insert into `configuration` (`config`, content_lang) values(%s, %s) "
                                , AmcWm::app()->db->quoteValue($config)
                                , AmcWm::app()->db->quoteValue($lang)
                        );
                    }else{
                        $confQueryNew[] = sprintf("update `configuration` set `config` = %s where content_lang = %s "
                                , AmcWm::app()->db->quoteValue($config)
                                , AmcWm::app()->db->quoteValue($lang)
                        );
                    }
                }
                
                if(count($confQueryNew)){
                    AmcWm::app()->db->createCommand(implode(";", $confQueryNew))->execute();
                }
                AmcWm::app()->user->setFlash('success', array('class' => 'flash-success', 'content' =>AmcWm::t('msgsbase.core','Configuration has been updated successfully')));
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model, 'configProperties' => $configProperties));
    }
}