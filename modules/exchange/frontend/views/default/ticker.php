<?php

$output = "";
$eachCols = ceil(count($data) / $rowLimit);
if ($eachCols) {
    $output .= CHtml::openTag("ul", array("id" => "stockSlider", "class" => "market_stock"));
    $itemsCount = count($data);
    for ($rowIndex = 1; $rowIndex <= $eachCols; $rowIndex++) {
        $output .= CHtml::openTag("li", array("style" => "right:0px !important"));
        $output .= '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;direction:rtl">' . PHP_EOL;
        $output .= "<tr>" . PHP_EOL;

        for ($childIndex = 0; $childIndex < $rowLimit && $itemsCount > 0; $childIndex++) {
            $value = current($data);
            $output .= '<td style="direction:ltr;white-space: nowrap;" >' . PHP_EOL;
            $output .= CHtml::openTag("span", array("class" => "ms_name"));
            $output .= " " . $value['company_name'] . " ";
            $output .= CHtml::closeTag("span");
            if ($value['difference_percentage'] < 0) {
                $class = "ms_dwn";
                $classPercentage = "ms_dwn-without";
            } elseif ($value['difference_percentage'] > 0) {
                $class = "ms_up";
                $classPercentage = "ms_up-without";
            } else {
                $class = "ms_nochange";
                $classPercentage = "ms_nochange-without";
            }
            $output .= CHtml::openTag("span", array("class" => $class));
            $output .= "%" . $value['difference_percentage'] . " ";
            $output .= CHtml::closeTag("span");
            $output .= CHtml::openTag("span", array("class" => $classPercentage));
            $output .= " " . number_format($value['closing_value'], 2, $floatingSeparator, $thousandSeparator) . " ";
            $output .= CHtml::closeTag("span");
            $output .= '</td>' . PHP_EOL;
            next($data);
            $itemsCount--;
        }

        $output .= "</tr>" . PHP_EOL;
        $output .= "</table>" . PHP_EOL;
        $output .= CHtml::closeTag("li");
    }
    $output .= CHtml::closeTag("ul");
}
echo $output;
