<?php

$colorClass = '';
$output = '<div class="table-grid stock-companies-grid">';
$output .= '<table class="items">';
$output .= "<tr>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Company Name') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Opening Value') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Closing Value') . "</th>";
$output .= "<th>" . AmcWm::t('msgsbase.companies', 'Difference %') . "</th>";
$output .= "</tr>";
foreach ($data as $key => $value) {
    $class = $key % 2 == 1 ? "odd" : "even";
    $output .= "<tr class =" . $class . ">";
    $output .= '<td class="company-name">' . $value['company_name'] . "</td>";
    if($value['difference_percentage'] > 0) {
        $colorClass = 'p-up';
        $output .= "<td class =" . $colorClass . ">" . number_format($value['opening_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . number_format($value['closing_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . $value['difference_percentage'] . "</td>";
    } elseif ($value['difference_percentage'] < 0) {
        $colorClass = 'p-down';
        $output .= "<td class =" . $colorClass . ">" . number_format($value['opening_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . number_format($value['closing_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . $value['difference_percentage'] . "</td>";
    }
    else{
        $colorClass = "";
        $output .= "<td class =" . $colorClass . ">" . number_format($value['opening_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . number_format($value['closing_value'], 2, $floatingSeparator, $thousandSeparator) . "</td>";
        $output .= "<td class =" . $colorClass . ">" . $value['difference_percentage'] . "</td>";
    }
    $output .= "</tr>";
}
$output .= "</table>";
$output .= "</div>" . PHP_EOL;

echo json_encode($output);
