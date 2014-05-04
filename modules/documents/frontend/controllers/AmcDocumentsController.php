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

class AmcDocumentsController extends FrontendController {

    public function actionIndex() {
        $keywords = Yii::app()->request->getParam('q');
        $category = (int) Yii::app()->request->getParam('c');
        $mediaSettings = new Settings("multimedia", false);
        $presentationSettings = $mediaSettings->settings;
        $view = "documents";
        $presentationViewPathAlias = null;
        if (isset($presentationSettings['options']['default']['integer']['presentationId']) && $presentationSettings['options']['default']['integer']['presentationId'] && $category == $presentationSettings['options']['default']['integer']['presentationId']) {

            //$_GET['c'] = $allOptions['default']['integer']['presentationId'];
            if (isset($presentationSettings['options']['default']['integer']['presentationViewInSite']) && $presentationSettings['options']['default']['integer']['presentationViewInSite']) {
                $view = "application.modules.multimedia.views.presentations.index";
                $presentationViewPathAlias = "application.modules.multimedia.views";
            } else {
                $view = "amcwm.modules.multimedia.frontend.views.presentations.index";
                $presentationViewPathAlias = "amcwm.modules.multimedia.frontend.views";
            }
            if (isset($presentationSettings['options']['default']['integer']['presentationMsgInSite']) && $presentationSettings['options']['default']['integer']['presentationMsgInSite']) {
                $presentationMsgAlias = 'application.modules.multimedia.messages';        
            } else {
                $presentationMsgAlias = 'amcwm.modules.multimedia.frontend.messages';        
            }
        }
        
        $parentCategory = (int) Yii::app()->request->getParam('id');
        if (!$category) {
            $category = $parentCategory;
        }


        $directory = new DocumentsData($keywords, $category, 10);
        $directory->setAdvancedParam("selectedCategory", Yii::app()->request->getParam('c'));
        $directory->setAdvancedParam("parentCategory", $parentCategory);
        $directory->generate();
        $menu = Yii::app()->request->getParam('menu');
        $id = Yii::app()->request->getParam('id');
        if ($menu && $id) {
            $parentCategoriesData = $directory->parentCategoryData();
            $viewAll = $parentCategoriesData['category_name'];
            $dirCategories = $directory->getAllCategories($id);
            if (!$dirCategories) {
                $dirCategories[$id] = $parentCategoriesData['category_name'];
            }
//            $dirCategories = array();
//            $dirCategories[$id] = $parentCategoriesData['category_name'];
//            foreach ($dirCategoriesDataset as $catId=>$catName){                
//                $dirCategories[$catId] = $catName;
//            }
        } else {
            $viewAll = AmcWm::t("msgsbase.core", 'View all');
            $dirCategories = $directory->getAllCategories();
        }
        $mediaSettings = $this->module->appModule->mediaSettings;
        $this->render($view, array(
            'category' => $category,
            'page' => (int) Yii::app()->request->getParam('page', 1),
            'dirCategoriesData' => $directory->categoryData(),
            'dirCategories' => $dirCategories,
            'viewAll' => $viewAll,
            'presentationViewPathAlias' => $presentationViewPathAlias,
            'presentationMsgAlias' => $presentationMsgAlias,
            'directoryData' => $directory->getResults(),
            'advancedParams' => $directory->getAdvancedParam(),
            'docsMediaSettings'=>$mediaSettings,
            'keywords' => $directory->getKeywords(),
        ));
    }

}
