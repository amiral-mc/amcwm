<?php
$this->breadcrumbs = array(
    'Issues' => array('index'),
    $model->issue_id,
);
?>

<h1>View Issues #<?php echo $model->issue_id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'issue_id',
        'issue_date',
        'published',
    ),
));
?>
