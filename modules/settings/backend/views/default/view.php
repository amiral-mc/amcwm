<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Configuration") => array('/backend/settings/default/index'),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Configuration");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/settings/default/update'), 'id' => 'edit_config', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Attributes'), 'url' => array('/backend/settings/attributes/index'), 'id' => 'add_attribute', 'image_id' => 'attributes'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'home', 'image_id' => 'back'),
    ),
));
?>

<br />
<br />
<?php
$tabs = array();

$configProperties = AmcWm::app()->params['configProperties'];
$languages = AmcWm::app()->params['languages'];
$arrayData = array();
foreach ($languages as $lang => $name) {
    $confQuery = sprintf("select config from configuration where content_lang = '$lang'");
    $confData = AmcWm::app()->db->createCommand($confQuery)->queryScalar();
    $element = "<table cellpadding='4'>";
    $confDataArray = unserialize(base64_decode($confData));
    foreach ($configProperties as $c) {
        $element .= "<tr>";
        if (isset($confDataArray['custom']['front']['site'][$c["name"]])) {
            $element .= "<td nowrap='nowrap'><b>".AmcWm::t("msgsbase.core", $c["name"]) . ":</b></td><td>" . $confDataArray['custom']['front']['site'][$c["name"]].  " </td>";
        }
        $element .= "</tr>";
    }
    $element .= "</table>";
    
    $tabs[$lang]["title"] = $name;
    $tabs[$lang]["content"] = $element;
}

$this->widget('TabView', array(
        'useCustomCSS' => false,
        'tabs' => $tabs        
    ));

?>
