<?php
$listRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "index", $this->getRoute())), $this->getParams());
$updateRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "update", $this->getRoute())), $this->getParams());
$repliesRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "replies", $this->getRoute())), $this->getParams());
$updateRoute['id'] = $model->comments->comment_id;
$repliesRoute['cid'] = $model->comments->comment_id;
$this->breadcrumbs[] = AmcWm::t("amcwm.core.backend.messages.comments", "View");
$this->sectionName = $model->comments->comment_header;
//$updateRoute
$this->widget('amcwm.core.widgets.tools.Tools', array(   
    'id' => 'tools-grid',   
    'items' => array(        
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => $updateRoute, 'id' => 'edit_comment', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcwm.core.backend.messages.comments", 'Replies'), 'url' => $repliesRoute, 'id' => 'manage_comment_replies', 'image_id' => 'replies'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => $listRoute, 'id' => 'back_2_list', 'image_id' => 'back'),
    ),
));
?>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model->comments,
    'attributes' => array(
        'comment_id',
        'comment_header',
        array(
            'name' => 'comment',
            'type' => 'html',
        ),
        array(
            'name' => 'published',
            'value' => ($model->comments->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'hide',
            'value' => ($model->comments->hide) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        'ip',        
    ),
));
?>
