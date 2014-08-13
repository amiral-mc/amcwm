<?php

$output = '<div class="table-grid stock-companies-grid">';
$output .= '<table class="items">';
$output .= "<tr>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Company Name') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Opening Value') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Closing Value') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Difference %') . "</th>";
$output .= "</tr>";
foreach ($data as $key => $value) {
    $class = $articleIndex % 2 == 1 ? "odd" : "even";
    $output .= "<tr class =" . $class . ">";
    $output .= "<td>" . $value['company_name'] . "</td>";
    $output .= "<td>" . $value['opening_value'] . "</td>";
    $output .= "<td>" . $value['closing_value'] . "</td>";
    $output .= "<td>" . $value['difference_percentage'] . "</td>";
    $output .= "</tr>";
}
$output .= "</table>";
$output .= "</div>" . PHP_EOL;

echo json_encode($output);
