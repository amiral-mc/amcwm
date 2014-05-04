<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Issues"),
);
$this->sectionName = AmcWm::t("msgsbase.core", AmcWm::t("msgsbase.core", "Manage Issues"));
$issueData = Issue::getInstance()->getIssue();
$tools = array();
$tools[] = array('visible' => (!$issueData['lastNotActive']['issue_id']), 'label' => AmcWm::t("msgsbase.core", 'Create'), 'url' => array('/backend/issues/default/create'), 'id' => 'add_article', 'image_id' => 'add');
$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Articles'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'issueArticles', 'refId' => 'issueId'), 'id' => 'add_article', 'image_id' => 'articles');
$tools[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'articles_list', 'image_id' => 'back');
//$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Sections'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'issueSections', 'refId' => 'issueId'), 'id' => 'link_sections', 'image_id' => 'sub-sections');

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools,
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div class="form">
    <?php
    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = Yii::app()->params["pageSize"];
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'issues-grid',
        'dataProvider' => $dataProvider,
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'issue_id',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            'issue_date',
            array(
                'name' => 'Articles',
                'value' => 'Yii::app()->db->createCommand("select count(*) from issues_articles where issue_id = " . $data->issue_id)->queryScalar();',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Articles'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
            array(
                'name' => 'publish_new',
                //'value' => '($data->getpublished) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'value' => '$data->checkPublish() ?  Html::link(CHtml::image(Yii::app()->baseUrl . "/images/publish.png", "", array("border" => 0)),array("/backend/issues/default/publish", "pissue"=>$data->issue_id)) : ""',
                'type' => 'html',
                'htmlOptions' => array('width' => '60', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Publish Issue'),
            ),
        ),
    ));
    ?>
</div>