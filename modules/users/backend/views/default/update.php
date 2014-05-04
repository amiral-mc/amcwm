<?php
$maillist = AmcWm::app()->request->getParam('maillist');
$model = $contentModel->getParentContent();
$user = $model->users;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Users") => array('/backend/users/default/index'),
    AmcWm::t("msgsbase.core", "Edit"),
);
$this->sectionName = $user->username;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_user', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Permissions'), 'url' => array('/backend/users/default/permissions', 'id' => $model->person_id), 'id' => 'user_permissions', 'image_id' => 'permissions'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/users/default/translate', 'id' => $model->person_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('visible'=> !$maillist, 'label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/users/default/index'), 'id' => 'users_list', 'image_id' => 'back'),
        array('visible'=> $maillist, 'label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/default/index'), 'id' => 'users_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>