<?php
$mediaPaths = Persons::getSettings()->mediaPaths;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.roles", "Roles") => array('/backend/users/groups/index'),
    AmcWm::t("msgsbase.roles", "View"),
);
$this->sectionName = $model->role;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/users/groups/create'), 'id' => 'add_user', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/users/groups/update', 'id' => $model->role_id), 'id' => 'edit_user', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/users/groups/index'), 'id' => 'users_list', 'image_id' => 'back'),
    ),
));
?>

<?php
$permissions = $this->widget('amcwm.core.widgets.ManageRolePermissions', array(
            'id' => 'manage-permissions-container',
            'model' => $model,
            'modules' => amcwm::app()->acl->getModules(),
        ), true);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'role_id',
        array(
            'label' => AmcWm::t("msgsbase.roles", 'Role'),
            'value' => $model->role,
        ),
        array(
            'label' => AmcWm::t("msgsbase.roles", 'Role Desc'),
            'value' => $model->role_desc,
        ),
        array(
            'label' => AmcWm::t("msgsbase.roles", 'Parent Role'),
            'value' => $model->parentRole->role,
        ),
        array(
            'label' => AmcWm::t("msgsbase.roles", 'Permissions'),
            'type' => 'raw',
            'value' => $permissions,
        ),
//        array(
//            'label' => AmcWm::t("msgsbase.roles", 'Published'),
//            'type' => 'html',
//            'value' => ($user->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0)),
//        ),
    ),
));
?>
