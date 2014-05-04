<?php
$listRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "index", $this->getRoute())), $this->getParams());
$updateRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "update", $this->getRoute())), $this->getParams());
$updateRoute['id'] = $model->comment_id;
$this->breadcrumbs[AmcWm::t("amcwm.core.backend.messages.comments", "Comments")] = $this->backRoute;
$this->breadcrumbs[$this->comment->comment_header] = $listRoute;
$this->breadcrumbs[] = AmcWm::t("amcwm.core.backend.messages.comments", "View");
$this->sectionName = $model->comment_header;
//$updateRoute
$this->widget('amcwm.core.widgets.tools.Tools', array(   
    'id' => 'tools-grid',   
    'items' => array(        
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => $updateRoute, 'id' => 'edit_comment', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => $listRoute, 'id' => 'back_2_list', 'image_id' => 'back'),
    ),
));
?>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'comment_id',
        'comment_header',
        array(
            'name' => 'comment',
            'type' => 'html',
        ),
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'hide',
            'value' => ($model->hide) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        'ip',        
    ),
));
?>
